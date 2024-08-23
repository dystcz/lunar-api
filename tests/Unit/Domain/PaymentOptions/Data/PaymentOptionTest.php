<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Lunar\DataTypes\Price as DataTypesPrice;
use Lunar\Models\Country;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

uses(TestCase::class, RefreshDatabase::class);

it('can manipulate cart during calculation', function () {
    /** @var TestCase $this */
    $country = Country::factory()->create();

    $currency = Currency::getDefault();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    $taxClass = TaxClass::query()->first();

    $purchasable = ProductVariant::factory()->create();

    Price::factory()->create([
        'price' => 600,
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasable->getMorphClass(),
        'priceable_id' => $purchasable->id,
    ]);

    // Closure which sets prices to 0
    $modifyCart = function (Cart $cart, PaymentOption $paymentOption) {
        $cart->subTotal = new DataTypesPrice(0, $cart->currency, 1);
        $cart->total = new DataTypesPrice(0, $cart->currency, 1);
        $cart->taxTotal = new DataTypesPrice(0, $cart->currency, 1);
        $cart->shippingTotal = new DataTypesPrice(0, $cart->currency, 1);
        $cart->paymentSubtotal = new DataTypesPrice(0, $cart->currency, 1);
        $cart->paymentTotal = new DataTypesPrice(0, $cart->currency, 1);

        return $cart;
    };

    $paymentOption = new PaymentOption(
        name: 'Gifr from heaven',
        driver: 'free',
        description: 'Everything for free payment option',
        identifier: 'FREE',
        price: new DataTypesPrice(0, $cart->currency, 1),
        taxClass: $taxClass
    );

    $paymentOption->modifyCartUsing($modifyCart);

    PaymentManifest::addOption($paymentOption);

    $cart->update([
        'payment_option' => $paymentOption->getIdentifier(),
    ]);

    $cart->paymentOption = $paymentOption;

    $this->assertCount(0, $cart->lines);

    $cart->add($purchasable, 1);

    $cart->calculate();

    $this->assertEquals(0, $cart->subTotal->value);
    $this->assertEquals(0, $cart->paymentSubTotal->value);
    $this->assertEquals(0, $cart->paymentTotal->value);
    $this->assertEquals(0, $cart->shippingTotal->value);
    $this->assertEquals(0, $cart->total->value);

    Config::set('lunar.pricing.stored_inclusive_of_tax', true);

    $cart->calculate();

    $this->assertEquals(0, $cart->paymentTotal->value);
    $this->assertEquals(0, $cart->total->value);
})->group('payment-options');
