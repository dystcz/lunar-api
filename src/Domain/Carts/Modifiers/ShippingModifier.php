<?php

namespace Dystcz\LunarApi\Domain\Carts\Modifiers;

use Lunar\Base\ShippingModifier as LunarShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

abstract class ShippingModifier extends LunarShippingModifier
{
    public static string $name = 'Shipping Option';

    public static string $description = 'Shipping option description';

    public static string $identifier = 'SODEL';

    public function handle(Cart $cart)
    {
        ShippingManifest::addOption(
            new ShippingOption(
                name: $this->getName(),
                description: $this->getDescription(),
                identifier: $this->getIdentifier(),
                price: $this->getPrice($cart),
                taxClass: $this->getTaxClass(),
                meta: $this->getMeta()
            )
        );
    }

    /**
     * Get the name for the shipping option.
     */
    public function getName(): string
    {
        return (new static)::$name;
    }

    /**
     * Get the description for the shipping option.
     */
    public function getDescription(): string
    {
        return (new static)::$description;
    }

    /**
     * Get the identifier for the shipping option.
     */
    public function getIdentifier(): string
    {
        return (new static)::$identifier;
    }

    /**
     * Get the price for the shipping option.
     */
    public function getPrice(Cart $cart): Price
    {
        return new Price(0, $this->getCurrency($cart), 1);
    }

    /**
     * Get the currency for the shipping option.
     */
    public function getCurrency(Cart $cart): Currency
    {
        return $cart->currency ?? Currency::getDefault();
    }

    /**
     * Get the tax class for the shipping option.
     */
    public function getTaxClass(): TaxClass
    {
        return TaxClass::first();
    }

    /**
     * Get the meta for the shipping option.
     */
    public function getMeta(): array
    {
        return [];
    }
}
