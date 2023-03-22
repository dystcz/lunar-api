<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

it('can read order details', function () {
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create();

    CartSession::use($cart);

    $order = $cart->createOrder();

    $response = $this
        ->jsonApi()
        ->includePaths(
            'productLines.purchasable.product',
            'productLines.purchasable.prices'
        )
        ->expects('orders')
        ->get('http://localhost/api/v1/orders/' . $order->getRouteKey());

    $response->assertFetchedOne($order)
        ->assertIsIncluded('order-lines', $order->lines->first());
});

it('returns unauthorized if the user doesn\'t own the order', function () {
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create();

    $order = $cart->createOrder();

    $response = $this
        ->jsonApi()
        ->includePaths('productLines')
        ->expects('orders')
        ->get('http://localhost/api/v1/orders/' . $order->getRouteKey());

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});