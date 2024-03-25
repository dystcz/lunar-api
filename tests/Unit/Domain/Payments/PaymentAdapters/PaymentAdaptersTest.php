<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Dystcz\LunarApi\Tests\Stubs\Payments\PaymentAdapters\TestPaymentAdapter;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    Event::fake(CartCreated::class);

    $this->cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create();

    $this->cart->createOrder();

    TestPaymentAdapter::register();
});

it('handles creation of payment intent', function () {
    /** @var TestCase $this */
    $payment = App::make(PaymentAdaptersRegister::class)->get('test');

    $intent = $payment->createIntent($this->cart);

    expect($intent)->toBeInstanceOf(PaymentIntent::class);
});

it('handles webhooks', function () {
    /** @var TestCase $this */
    $payment = App::make(PaymentAdaptersRegister::class)->get('test');

    $response = $payment->handleWebhook(new Request());

    expect($response)->toBeInstanceOf(JsonResponse::class);
});
