<?php

namespace Dystcz\LunarApi\Tests\Stubs\Carts\Modifiers;

use Dystcz\LunarApi\Domain\Carts\Modifiers\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class TestShippingModifier extends ShippingModifier
{
    public static string $name = 'Basic Delivery';

    public static string $description = 'A basic delivery option';

    public static string $identifier = 'FFCDEL';

    public function handle(Cart $cart)
    {
        parent::handle($cart);
    }

    /**
     * Get the price for the shipping option.
     */
    public function getPrice(Cart $cart): Price
    {
        return new Price(500, $this->getCurrency($cart), 1);
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
