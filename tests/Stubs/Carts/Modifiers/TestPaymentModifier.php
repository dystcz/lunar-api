<?php

namespace Dystcz\LunarApi\Tests\Stubs\Carts\Modifiers;

use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Dystcz\LunarApi\Domain\PaymentOptions\Modifiers\PaymentModifier;
use Lunar\DataTypes\Price;
use Lunar\Models\Contracts\Cart as CartContract;
use Lunar\Models\Contracts\Currency as CurrencyContract;
use Lunar\Models\Contracts\TaxClass as TaxClassContract;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class TestPaymentModifier extends PaymentModifier
{
    public function handle(CartContract $cart): void
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
    public function getCurrency(CartContract $cart): CurrencyContract
    {
        return $cart->currency ?? Currency::modelClass()::query()->first();
    }

    /**
     * Get the tax class for the shipping option.
     */
    public function getTaxClass(): TaxClassContract
    {
        return TaxClass::modelClass()::query()->first() ?? TaxClass::modelClass()::factory()->create();
    }
}
