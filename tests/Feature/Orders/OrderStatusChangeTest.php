<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Enums\OrderStatus;
use Dystcz\LunarApi\Domain\Orders\Events\OrderStatusChanged;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = CartFactory::new()
        ->withAddresses()
        ->withLines()
        ->create();

    $order = $cart->createOrder();

    $this->order = Order::query()->where($order->getKeyName(), $order->getKey())->firstOrFail();
});

it('sends order status changed event after order status was changed', function () {
    /** @var TestCase $this */
    Event::fake(OrderStatusChanged::class);

    $this->order->update(['status' => OrderStatus::PENDING_PAYMENT->value]);

    Event::assertDispatched(
        OrderStatusChanged::class,
        fn (OrderStatusChanged $event) => $event->order->getKey() === $this->order->getKey(),
    );
})->group('orders');
