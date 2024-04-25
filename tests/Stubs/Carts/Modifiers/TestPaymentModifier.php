<?php

namespace Dystcz\LunarApi\Tests\Stubs\Carts\Modifiers;

use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Dystcz\LunarApi\Domain\PaymentOptions\Modifiers\PaymentModifier;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class TestPaymentModifier extends PaymentModifier
{
    public function handle(Cart $cart): void
    {
        PaymentManifest::addOption(
            new PaymentOption(
                name: 'PayPal',
                driver: 'paypal',
                description: 'PayPal payment option',
                identifier: 'PAYPAL',
                price: new Price(1000, $this->getCurrency($cart), 1),
                taxClass: $this->getTaxClass(),
                meta: [],
            )
        );
    }

    /**
     * Get the currency for the shipping option.
     */
    public function getCurrency(Cart $cart): Currency
    {
        return $cart->currency ?? Currency::query()->first();
    }

    /**
     * Get the tax class for the shipping option.
     */
    public function getTaxClass(): TaxClass
    {
        return TaxClass::query()->first() ?? TaxClass::factory()->create();
    }
}
