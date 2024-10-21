<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Checkout\Enums\CheckoutProtectionStrategy;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

it('can read order details without signature when user is logged in and owns the order', function () {
    /** @var TestCase $this */

    /** @var User $user */
    $user = User::factory()->create();

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory
        ->withAddresses()
        ->withLines()
        ->for($user)
        ->create();

    CartSession::use($cart);

    $order = $cart->createOrder();

    $order = Order::query()
        ->where($order->getKeyName(), $order->getKey())
        ->first();

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->includePaths(
            'product_lines.purchasable.product',
            'product_lines.purchasable.prices',
            'product_lines.purchasable.images',
            'product_lines.currency',
            'customer',
            'order_addresses',
        )
        ->expects('orders')
        ->get('/api/v1/orders/'.$order->getRouteKey());

    $response
        ->assertFetchedOne($order)
        ->assertIsIncluded('order_lines', $order->lines->first());

})->group('orders');

it('can read order details when accessing order with valid signature', function () {
    /** @var TestCase $this */
    Config::set('lunar-api.general.checkout.checkout_protection_strategy', CheckoutProtectionStrategy::SIGNATURE);

    /** @var User $user */
    $user = User::factory()->create();

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory
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
                'agree' => true,
                'create_user' => false,
            ],
        ])
        ->post(serverUrl('/carts/-actions/checkout'));

    $signedUrl = $response->json()['links']['self.signed'];

    $order = Order::query()
        ->where('cart_id', $cart->getKey())
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

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory->withAddresses()
        ->withLines()
        ->create();

    $order = $cart->createOrder();

    $order = Order::query()
        ->where($order->getKeyName(), $order->getKey())
        ->first();

    $response = $this
        ->jsonApi()
        ->includePaths('product_lines')
        ->expects('orders')
        ->get(serverUrl("/orders/{$order->getRouteKey()}"));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
})->group('orders', 'policies');
