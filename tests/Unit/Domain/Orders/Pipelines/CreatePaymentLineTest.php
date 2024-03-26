<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\OrderLines\Models\OrderLine;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Orders\Pipelines\CreatePaymentLine;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\DataTypes\Price as DataTypesPrice;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

uses(TestCase::class, RefreshDatabase::class);

it('can run add payment line pipeline', function () {
    /** @var TestCase $this */
    $currency = Currency::factory()->create();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    $taxClass = TaxClass::query()->first();

    $paymentOption = new PaymentOption(
        name: 'Evil payments',
        driver: 'satan',
        description: 'Your money is mine',
        identifier: 'HELL',
        price: new DataTypesPrice(66600, $cart->currency, 1),
        taxClass: $taxClass
    );

    PaymentManifest::addOption($paymentOption);

    $cart->update([
        'payment_option' => $paymentOption->getIdentifier(),
    ]);

    $order = Order::factory()->create([
        'cart_id' => $cart->id,
    ]);

    $order = App::make(CreatePaymentLine::class)->handle($order, function ($order) {
        return $order;
    });

    $this->assertCount(1, $order->paymentLines);
    $this->assertEquals('HELL', $order->paymentLines->first()->identifier);
})->group('orders', 'payment-lines');

it('can update payment line if exists', function () {
    /** @var TestCase $this */
    $currency = Currency::factory()->create();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    $taxClass = TaxClass::query()->first();

    $paymentOption = new PaymentOption(
        name: 'Fair payments',
        driver: 'fair',
        description: 'You will survive',
        identifier: 'FAIR',
        price: new DataTypesPrice(100, $cart->currency, 1),
        taxClass: $taxClass
    );

    PaymentManifest::addOption($paymentOption);

    $cart->update([
        'payment_option' => $paymentOption->getIdentifier(),
    ]);

    $order = Order::factory()->create([
        'cart_id' => $cart->id,
    ]);

    OrderLine::factory()->create([
        'identifier' => 'FAIR',
        'purchasable_type' => PaymentOption::class,
        'type' => 'payment',
        'order_id' => $order->id,
    ]);

    $order = App::make(CreatePaymentLine::class)->handle($order->refresh(), function ($order) {
        return $order;
    });

    $this->assertCount(1, $order->paymentLines);
    $this->assertEquals('FAIR', $order->paymentLines->first()->identifier);
})->group('orders', 'payment-lines');
