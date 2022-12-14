<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Database\Factories\Store;

use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->simple(),
        ];
    }

    public function paid()
    {
        $date = Carbon::now();

        return $this->state([
            'paid_at' => $date,
            'status' => 'paid',
            'transaction_id' => "test-{$date->timestamp}",
        ]);
    }

    public function shopify()
    {
        return $this->state([
            // Doesn't need to be a gid for tests.
            'transaction_id' => Order::PROVIDER_SHOPIFY.'-'.Carbon::now()->timestamp,
        ]);
    }

    public function incart()
    {
        return $this->state(['status' => Order::STATUS_INCART]);
    }

    public function processing()
    {
        return $this->state(['status' => Order::STATUS_PAYMENT_REQUESTED]);
    }

    public function checkout()
    {
        return $this->state(['status' => Order::STATUS_PAYMENT_APPROVED]);
    }

    public function shipped()
    {
        return $this->state(['status' => Order::STATUS_SHIPPED]);
    }
}
