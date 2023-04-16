<?php

namespace Dystcz\LunarApi\Tests\Feature\Orders;

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

it('can read order details', function () {
    Event::fake(CartCreated::class);

    $this->actingAs($user = User::factory()->create());

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->for($user)
        ->create();

    CartSession::use($cart);

    $order = $cart->createOrder();

    $response = $this
        ->jsonApi()
        ->includePaths(
            'productLines.purchasable.product',
            'productLines.purchasable.prices',
            'productLines.purchasable.images',
            'productLines.currency',
            'customer',
            'addresses',
        )
        ->expects('orders')
        ->get('/api/v1/orders/'.$order->getRouteKey());

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
        ->get('http://localhost/api/v1/orders/'.$order->getRouteKey());

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});
