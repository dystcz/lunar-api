<?php

namespace Dystcz\LunarApi\Tests\Stubs\Carts\Modifiers;

use Closure;
use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class TestShippingModifier extends ShippingModifier
{
    public function handle(Cart $cart, Closure $next): mixed
    {
        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Basic Delivery',
                description: 'A basic delivery option',
                identifier: 'FFCDEL',
                price: new Price(500, $this->getCurrency($cart), 1),
                taxClass: $this->getTaxClass(),
                meta: [],
            )
        );

        if ($cart->shippingAddress?->country?->iso2 === 'CZ') {
            ShippingManifest::addOption(
                new ShippingOption(
                    name: 'Czech delivery',
                    description: 'Czech delivery option',
                    identifier: 'CZEDEL',
                    price: new Price(600, $this->getCurrency($cart), 1),
                    taxClass: $this->getTaxClass(),
                    meta: [],
                )
            );
        }

        return $next($cart);
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
