<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create();

    $this->order = $cart->createOrder();
    $this->cart = $cart;
});

test('a payment intent can be created', function (string $paymentMethod) {
    $url = URL::temporarySignedRoute(
        'v1.orders.createPaymentIntent', now()->addDays(28), ['order' => $this->order->id]
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

    expect($response->json('meta.payment_intent.id'))
        ->toBe($this->cart->fresh()->meta->payment_intent);
})
    ->skip()
    ->with(['cash-in-hand']);
