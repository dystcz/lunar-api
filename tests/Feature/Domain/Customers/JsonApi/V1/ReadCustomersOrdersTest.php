<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list customers orders', function () {
    /** @var TestCase $this */
    $customer = Customer::factory()
        ->withOrder()
        ->has(User::factory())
        ->create();

    $this->actingAs($customer->users->first());

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
        ->includePaths(
            'product_lines.purchasable.product',
            'product_lines.purchasable.prices',
        )
        ->get(serverUrl("/customers/{$customer->getRouteKey()}/orders"));

    $orderLine = $customer->orders->first()->lines->first();

    $response->assertFetchedMany($expected)
        ->assertIsIncluded('order_lines', $orderLine)
        ->assertIsIncluded('product_variants', $orderLine->purchasable)
        ->assertIsIncluded('products', $orderLine->purchasable->product)
        ->assertIsIncluded('prices', $orderLine->purchasable->prices->first());
})->group('orders');
