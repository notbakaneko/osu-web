<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace App\Libraries\Payments;

use App\Models\Store\Order;
use Carbon\Carbon;
use Sentry\Severity;
use Sentry\State\Scope;

class PaypalPaymentProcessor extends PaymentProcessor
{
    public function getCountryCode(): ?string
    {
        return $this['residence_country'];
    }

    public function getOrderNumber(): ?string
    {
        // If refund, there might not be an invoice id in production.
        if ($this->getNotificationType() === NotificationType::REFUND) {
            return $this['invoice'] ?? $this['item_number1'];
        } else {
            return $this['invoice'];
        }
    }

    public function getParentTransactionId(): ?string
    {
        return $this['parent_txn_id'];
    }

    public function getPaymentProvider(): string
    {
        return Order::PROVIDER_PAYPAL;
    }

    public function getPaymentTransactionId(): string
    {
        return $this['txn_id'];
    }

    public function getPaymentAmount(): float
    {
        if ($this->getNotificationType() === NotificationType::REFUND) {
            return (float) $this['mc_gross'] + $this['mc_fee'];
        } else {
            return (float) $this['mc_gross'];
        }
    }

    public function getPaymentDate(): \DateTimeInterface
    {
        return Carbon::parse($this['payment_date'])->setTimezone('UTC');
    }

    public function isTest(): bool
    {
        return get_bool(presence($this['test_ipn'])) ?? false;
    }

    public function getNotificationType(): string
    {
        static $paymentStatuses = ['Completed'];
        static $refundStatuses = ['Refunded', 'Reversed', 'Canceled_Reversal'];
        static $pendingStatuses = ['Pending'];
        static $rejectedStatuses = ['Declined', 'Denied', 'Expired', 'Failed', 'Voided'];

        $status = $this->getNotificationTypeRaw();
        // shouldIgnore needs to be first because txn_type needs to be checked in priority over payment_status for ignored notifications,
        // while payment_status has priority for other notifications.
        if ($this->shouldIgnore($status)) {
            return NotificationType::IGNORED;
        } elseif (in_array($status, $paymentStatuses, true)) {
            return NotificationType::PAYMENT;
        } elseif (in_array($status, $refundStatuses, true)) {
            return NotificationType::REFUND;
        } elseif (in_array($status, $pendingStatuses, true)) {
            return NotificationType::PENDING;
        } elseif (in_array($status, $rejectedStatuses, true)) {
            return NotificationType::REJECTED;
        } else {
            return "unknown__{$status}";
        }
    }

    public function getNotificationTypeRaw(): string
    {
        return $this['payment_status'] ?? $this['txn_type'];
    }

    public function validateTransaction(): bool
    {
        $this->signature->assertValid();

        $order = $this->getOrder();
        // order should exist
        if ($order === null) {
            $this->validationErrors()->add('order', '.order.invalid');

            return false;
        }

        if ($this['receiver_id'] !== $GLOBALS['cfg']['payments']['paypal']['merchant_id']) {
            $this->validationErrors()->add('receiver_id', '.param.invalid', ['param' => 'receiver_id']);
        }

        // order should be in the correct state
        if ($this->isPaymentOrPending()) {
            if ($order->isPendingPaymentCapture() === false) {
                $this->validationErrors()->add(
                    'order.status',
                    '.order.status.not_checkout',
                    ['state' => $order->status]
                );
            }

            if ($this['mc_currency'] !== 'USD') {
                $this->validationErrors()->add(
                    'mc_currency',
                    '.purchase.checkout.currency',
                    ['type' => $this['mc_currency']]
                );
            }

            \Log::debug("purchase.checkout.amount: {$this->getPaymentAmount()}, {$order->getTotal()}");
            if (compare_currency($this->getPaymentAmount(), $order->getTotal()) !== 0) {
                $this->validationErrors()->add(
                    'purchase.checkout.amount',
                    '.purchase.checkout.amount',
                    ['expected' => $order->getTotal(), 'actual' => $this->getPaymentAmount()]
                );
            }
        }

        $this->validatePendingStatus();

        // just check if IPN transaction id is as expected with the Paypal v2 API.
        $capturedId = $this->getOrder()->getProviderReference();
        $transactionId = $this->getNotificationType() === NotificationType::REFUND
            ? $this->getParentTransactionId()
            : $this->getPaymentTransactionId();

        if ($capturedId !== $transactionId) {
            app('sentry')->getClient()->captureMessage(
                'IPN transactionId does not match captured payment id',
                Severity::warning(),
                (new Scope())
                    ->setExtra('order_id', $order->getKey())
                    ->setExtra('txn_id', $this->getPaymentTransactionId())
                    ->setExtra('parent_txn_id', $this->getParentTransactionId())
                    ->setExtra('captured_id', $capturedId)
            );
        }

        return $this->validationErrors()->isEmpty();
    }

    /**
     * Fetches the Order corresponding to this payment and memoizes it.
     */
    protected function getOrder(): ?Order
    {
        return $this->memoize(__FUNCTION__, function () {
            // Order number can come from anywhere when paypal is involved /tableflip.
            // Attempt to find order number, else fallback to paypal's parent transaction ID for refunds,
            //  since the IPN might not include the invoice id.
            if ($this->getNotificationType() === NotificationType::REFUND && $this->getOrderNumber() === null) {
                return Order::withPayments()
                    ->wherePaymentTransactionId($this['parent_txn_id'], Order::PROVIDER_PAYPAL)
                    ->first();
            }

            return Order::withPayments()
                ->whereOrderNumber($this->getOrderNumber())
                ->first();
        });
    }

    private function isPaymentOrPending()
    {
        static $types = [NotificationType::PAYMENT, NotificationType::PENDING];

        return in_array($this->getNotificationType(), $types, true);
    }

    private function shouldIgnore($status)
    {
        static $ignoredStatuses = ['new_case'];
        // txn_types we ignore that might also have payment_status set.
        static $ignoredTxnTypes = ['masspay', 'send_money'];

        return in_array($status, $ignoredStatuses, true)
            || in_array($this['txn_type'], $ignoredTxnTypes, true);
    }

    /**
     * Runs validations related to Pending status;
     * adds errors into validationErrors(), does not return anything.
     */
    private function validatePendingStatus()
    {
        if ($this->getNotificationType() !== NotificationType::PENDING) {
            return;
        }

        // only recognize echecks
        if ($this['pending_reason'] !== 'echeck') {
            $this->validationErrors()->add(
                'pending_reason',
                '.paypal.not_echeck',
                ['actual' => $this['pending_reason']]
            );
        }
    }
}
