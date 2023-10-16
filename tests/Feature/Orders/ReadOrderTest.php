<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

it('can read order details when user is logged in and owns the order', function () {
    /** @var TestCase $this */
    Event::fake(CartCreated::class);

    /** @var User $user */
    $user = User::factory()->create();

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->for($user)
        ->create();

    CartSession::use($cart);

    $order = $cart->createOrder();

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->includePaths(
            'product_lines.purchasable.product',
            'product_lines.purchasable.prices',
            'product_lines.purchasable.images',
            'product_lines.currency',
            'customer',
            'addresses',
        )
        ->expects('orders')
        ->get('/api/v1/orders/'.$order->getRouteKey());

    $response
        ->assertFetchedOne($order)
        ->assertIsIncluded('order-lines', $order->lines->first());

})->group('orders');

it('can read order details when accessing order with valid signature', function () {
    /** @var TestCase $this */
    Event::fake(CartCreated::class);

    Config::set('lunar-api.domains.orders.signed_show_route', true);

    /** @var User $user */
    $user = User::factory()->create();

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->for($user)
        ->create();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $signedUrl = $response->json()['links']['self.signed'];

    $order = Order::query()
        ->where('id', $response->getId())
        ->first();

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->get($signedUrl);

    $response
        ->assertFetchedOne($order)
        ->assertSuccessful()
        ->assertLinks([
            'self.signed' => $response->json()['links']['self.signed'],
            'create-payment-intent.signed' => $response->json()['links']['create-payment-intent.signed'],
            'mark-order-pending-payment.signed' => $response->json()['links']['mark-order-pending-payment.signed'],
            'mark-order-awaiting-payment.signed' => $response->json()['links']['mark-order-awaiting-payment.signed'],
            'check-order-payment-status.signed' => $response->json()['links']['check-order-payment-status.signed'],
        ]);

})->group('orders');

it('returns unauthorized if the user does not own the order', function () {
    /** @var TestCase $this */
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
})->group('orders');
