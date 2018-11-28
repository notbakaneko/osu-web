<?php

/**
 *    Copyright 2015-2017 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Models\Store;

/**
 * Records transaction data from payment providers.
 *
 * @property int $id
 * @property int $order_id
 * @property boolean $cancelled
 * @property string $transaction_id
 * @property string $provider
 * @property Carbon\Carbon $paid_at
 * @property Carbon\Carbon|null $created_at
 * @property Carbon\Carbon|null $updated_at
 * @property string|null $country_code
 */
class Payment extends Model
{
    protected $casts = [
        'cancelled' => 'boolean',
    ];
    protected $dates = ['paid_at'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getOrderTransactionId()
    {
        return "{$this->provider}-{$this->transaction_id}";
    }

    public function cancel()
    {
        $payment = $this->replicate();
        $payment->cancelled = true;
        $payment->saveOrExplode();
    }
}
