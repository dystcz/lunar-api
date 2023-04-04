<?php

namespace Dystcz\LunarApi\Tests\Stubs\Carts\Modifiers;

use Dystcz\LunarApi\Domain\Carts\Modifiers\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class CzechOnlyTestShippingModifier extends ShippingModifier
{
    public static string $name = 'Czech delivery';

    public static string $description = 'Czech delivery option';

    public static string $identifier = 'CZEDEL';

    public function handle(Cart $cart)
    {
        $cart->load(['shippingAddress']);

        if ($cart->shippingAddress->country->iso2 !== 'CZ') {
            return;
        }

        parent::handle($cart);
    }

    /**
     * Get the price for the shipping option.
     */
    public function getPrice(Cart $cart): Price
    {
        return new Price(600, $this->getCurrency($cart), 1);
    }

    /**
     * Get the currency for the shipping option.
     */
    public function getCurrency(Cart $cart): Currency
    {
        return $cart->currency ?? Currency::first();
    }

    /**
     * Get the tax class for the shipping option.
     */
    public function getTaxClass(): TaxClass
    {
        return TaxClass::first() ?? TaxClass::factory()->create();
    }
}
