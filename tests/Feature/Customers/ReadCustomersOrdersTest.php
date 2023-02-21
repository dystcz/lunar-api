<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('works', function () {
    $customer = Customer::factory()
        ->withOrder()
        ->create();

    $expected = $customer->orders()->get()
        ->map(fn (Order $order) => [
            'type' => 'orders',
            'id' => (string) $order->getRouteKey(),
            'attributes' => [
                'status' => $order->status,
            ],
        ])->all();

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->includePaths('lines.purchasable.product', 'lines.purchasable.prices')
        ->get("/api/v1/customers/{$customer->getRouteKey()}/orders");

    $orderLine = $customer->orders->first()->lines->first();

    $response->assertFetchedMany($expected)
        ->assertIsIncluded('order-lines', $orderLine)
        ->assertIsIncluded('variants', $orderLine->purchasable)
        ->assertIsIncluded('products', $orderLine->purchasable->product)
        ->assertIsIncluded('prices', $orderLine->purchasable->prices->first());
});
