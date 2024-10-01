<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Lunar\DataTypes\Price as DataTypesPrice;
use Lunar\Models\Country;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Config::set('lunar.payments.default', 'paypal');
});

it('can calculate payment option price', function () {
    /** @var TestCase $this */
    $country = Country::factory()->create();

    $currency = Currency::getDefault();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    $taxClass = TaxClass::query()->first();

    /** @var ProductVariant $purchasable */
    $purchasable = ProductVariantFactory::new()->state(['stock' => 1])->create();

    Price::factory()->create([
        'price' => 400,
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasable->getMorphClass(),
        'priceable_id' => $purchasable->id,
    ]);

    $paymentOption = new PaymentOption(
        name: 'Adyen',
        driver: 'adyen',
        description: 'Adyen payment option',
        identifier: 'ADYEN',
        price: new DataTypesPrice(600, $cart->currency, 1),
        taxClass: $taxClass
    );

    PaymentManifest::addOption($paymentOption);

    $cart->update([
        'payment_option' => $paymentOption->getIdentifier(),
    ]);

    $cart->paymentOption = $paymentOption;

    $this->assertCount(0, $cart->lines);

    $cart->add($purchasable, 1);

    $cart->calculate();

    $this->assertEquals(400, $cart->subTotal->value);
    $this->assertEquals(600, $cart->paymentSubTotal->value);
    $this->assertEquals(720, $cart->paymentTotal->value);
    $this->assertEquals(1200, $cart->total->value);

    Config::set('lunar.pricing.stored_inclusive_of_tax', true);

    $cart->recalculate();

    $this->assertEquals(600, $cart->paymentTotal->value);
    $this->assertEquals(1000, $cart->total->value);
})->group('carts', 'carts.payment-options');
