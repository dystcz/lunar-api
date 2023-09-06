<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Events\OrderPaid;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Event::fake(CartCreated::class);

    // NOTE: Provide an approved PayPal order id
    $this->payment_intent_id = '4LM91966KN759705H';

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create([
            'meta' => [
                'payment_intent' => $this->payment_intent_id,
            ],
        ]);

    $cart->createOrder();

    $cart->draftOrder->update([
        'meta' => [
            'payment_intent' => $this->payment_intent_id,
        ],
    ]);

    $this->cart = $cart;
    $this->order = $cart->order;
});

it('can handle succeeded event', function () {
    Event::fake(OrderPaid::class);

    $data = json_decode(file_get_contents(__DIR__.'/../../Stubs/Paypal/order.approved.json'), true);

    $data['body']['resource']['id'] = $this->payment_intent_id;

    $this->post('/paypal/webhook', $data['body'])->assertSuccessful();

    Event::assertDispatched(OrderPaid::class);
})->skip('Requires a valid PayPal order id in a specific state');
