<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Enums\OrderStatus;
use Dystcz\LunarApi\Domain\Orders\Events\OrderStatusChanged;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = CartFactory::new()
        ->withAddresses()
        ->withLines()
        ->create();

    $this->order = $cart->createOrder();
});

test('can change order status to pending payment', function () {
    /** @var TestCase $this */
    Event::fake(OrderStatusChanged::class);

    $url = URL::signedRoute(
        'v1.orders.markPendingPayment', ['order' => $this->order->id]
    );

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'orders',
            'id' => (string) $this->order->getRouteKey(),
        ])
        ->patch($url);

    $response->assertSuccessful();

    $id = $response->getId();

    $this->assertDatabaseHas($this->order->getTable(), [
        'id' => $id,
        'status' => OrderStatus::PENDING_PAYMENT->value,
    ]);

    Event::assertDispatched(
        OrderStatusChanged::class,
        fn (OrderStatusChanged $event) => $event->order->id === $this->order->id,
    );
});
