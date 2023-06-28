<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Events\OrderPaid;
use Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentCanceled;
use Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentFailed;
use Dystcz\LunarApi\Domain\Payments\Actions\CreateStripePaymentIntent;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Stripe\PaymentIntent;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create();

    $cart->createOrder();

    $this->intent = App::make(CreateStripePaymentIntent::class)($cart);

    $cart->update([
        'meta' => [
            'payment_intent' => $this->intent->id,
        ],
    ]);

    $cart->order->update([
        'meta' => [
            'payment_intent' => $this->intent->id,
        ],
    ]);

    $this->cart = $cart;
    $this->order = $cart->order;
});

it('can handle succeeded event', function () {
    Event::fake(OrderPaid::class);

    $data = json_decode(file_get_contents(__DIR__.'/../../Stubs/Stripe/payment_intent.succeeded.json'), true);

    $data['data']['object']['id'] = $this->cart->meta->payment_intent;

    PaymentIntent::update($this->intent->id, [
        'payment_method_types' => ['card'],
        'payment_method' => 'pm_card_visa',
    ]);

    $this->intent->confirm();

    $this->post('/stripe/webhook', $data)->assertSuccessful();

    Event::assertDispatched(OrderPaid::class);
});

it('can handle canceled event', function () {
    Event::fake(OrderPaymentCanceled::class);

    $data = json_decode(file_get_contents(__DIR__.'/../../Stubs/Stripe/payment_intent.canceled.json'), true);

    $data['data']['object']['id'] = $this->cart->meta->payment_intent;

    $this->post('/stripe/webhook', $data)->assertSuccessful();

    Event::assertDispatched(OrderPaymentCanceled::class);
});

it('can handle payment_failed event', function () {
    Event::fake(OrderPaymentFailed::class);

    $data = json_decode(file_get_contents(__DIR__.'/../../Stubs/Stripe/payment_intent.payment_failed.json'), true);

    $data['data']['object']['id'] = $this->cart->meta->payment_intent;

    $this->post('/stripe/webhook', $data)->assertSuccessful();

    Event::assertDispatched(OrderPaymentFailed::class);
});
