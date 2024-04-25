<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */

    /** @var Cart $cart */
    $cart = CartFactory::new()
        ->withAddresses()
        ->withLines()
        ->create();

    $this->cart = $cart;

    $order = $cart->createOrder();

    $this->order = Order::query()
        ->where($order->getKeyName(), $order->getKey())
        ->first();
});

test('a payment intent can be created', function (string $paymentMethod) {
    /** @var TestCase $this */
    $url = URL::signedRoute(
        'v1.orders.createPaymentIntent',
        ['order' => $this->order->getRouteKey()],
    );

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'orders',
            'id' => (string) $this->order->getRouteKey(),
            'attributes' => [
                'payment_method' => $paymentMethod,
            ],
        ])
        ->post($url);

    $response->assertSuccessful();
})
    ->with(['cash-in-hand'])
    ->group('payments');

test('a payment intent can be created with custom amount', function (string $paymentMethod) {
    /** @var TestCase $this */
    $url = URL::signedRoute(
        'v1.orders.createPaymentIntent',
        ['order' => $this->order->getRouteKey()],
    );

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'orders',
            'id' => (string) $this->order->getRouteKey(),
            'attributes' => [
                'payment_method' => $paymentMethod,
                'amount' => 1000,
            ],
        ])
        ->post($url);

    $response->assertSuccessful();

})
    ->with(['cash-in-hand'])
    ->group('payments');

test('a payment cannot be created withhout correct signature', function (string $paymentMethod) {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'orders',
            'id' => (string) $this->order->getRouteKey(),
            'attributes' => [
                'payment_method' => $paymentMethod,
                'amount' => 1000,
            ],
        ])
        ->post(route(
            'v1.orders.createPaymentIntent',
            ['order' => $this->order->getRouteKey()],
        ));

    $response->assertErrorStatus([
        'detail' => 'This action is unauthorized.',
        'status' => '403',
        'title' => 'Forbidden',
    ]);
})
    ->with(['cash-in-hand'])
    ->group('payments', 'policies');
