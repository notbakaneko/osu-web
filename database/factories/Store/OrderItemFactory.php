<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Database\Factories\Store;

use App\Models\Store\ExtraDataSupporterTag;
use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\Store\Product;
use Database\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'cost' => 12.0,
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => 1,
        ];
    }

    public function supporterTag(int $duration = 1, bool $hidden = false)
    {
        return $this->state(function (array $self) use ($duration, $hidden) {
            $order = Order::find($self['order_id']);
            $user = $order->user;

            return [
                'cost' => 4,
                'product_id' => Product::customClass(Product::SUPPORTER_TAG_NAME)->first(),
                'extra_data' => fn () => new ExtraDataSupporterTag([
                    'duration' => $duration,
                    'hidden' => $hidden,
                    'target_id' => $user->getKey(),
                    'username' => $user->username,
                ]),
            ];
        });
    }

    public function usernameChange()
    {
        return $this->state([
            'product_id' => Product::customClass('username-change')->first(),
            'cost' => 0,
            'extra_info' => 'new_username',
        ]);
    }
}
