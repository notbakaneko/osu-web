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

namespace Tests;

use App\Libraries\OrderCheckout;
use App\Models\Country;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Store\Product;
use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use Carbon\Carbon;
use TestCase;

class OrderCheckoutTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'osu_featurevotes' => 0,
            'osu_subscriptionexpiry' => Carbon::now(),
        ]);

        $this->order = factory(Order::class)->create([
            'user_id' => $this->user->user_id,
        ]);

        // crap test
        $this->tournament = factory(Tournament::class)->create();
        $this->banner = Product::customClass('mwc7-supporter')->orderBy('product_id', 'desc')->first();
        $this->findOrSeed();
    }

    private function findOrSeed()
    {
        // TODO: factory that creates related items properly? or just use fixtures.
        $banner = Product::customClass('mwc7-supporter')->orderBy('product_id', 'desc')->first();
        if ($banner) {
            $matches = [];
            preg_match('/.+\((?<country>.+)\)$/', $this->banner->name, $matches);
            $country = Country::where('name', $matches['country'])->first();
        } else {
            $country = factory(Country::class)->create();
            (new \ProductSeeder())->seedBanners();
            $banner = Product::customClass('mwc7-supporter')->orderBy('product_id', 'desc')->first();
        }

        $this->banner = $banner;
        $this->country = $country;
    }

    public function testBannerMissingCountry()
    {
        $orderItem = $this->createBannerOrderItem($this->banner, [
            'extra_data' => ['tournament_id' => $this->tournament->tournament_id]
        ]);

        $checkout = new OrderCheckout($this->order);
        $errors = $checkout->validate();

        $itemErrors = data_get($errors, "orderItems.{$orderItem->id}");
        $this->assertNotEmpty($errors);
    }

    public function testBannerMissingTournamentId()
    {
        $orderItem = $this->createBannerOrderItem($this->banner, ['extra_data' => ['cc' => $this->country->acronym]]);

        $checkout = new OrderCheckout($this->order);
        $errors = $checkout->validate();

        $itemErrors = data_get($errors, "orderItems.{$orderItem->id}");
        $this->assertNotEmpty($errors);
    }

    private function createBannerOrderItem($product, $overrides = [])
    {
        $base = [
            'product_id' => $product->product_id,
            'order_id' => $this->order->order_id,
            'cost' => $product->cost,
            'extra_data' => [
                'tournament_id' => $this->tournament->tournament_id,
                'cc' => $this->country->acronym,
            ],
        ];

        return factory(OrderItem::class)->create(array_merge($base, $overrides));
    }
}
