<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Domain\Carts\Factories\CartAddressFactory;
use Lunar\Models\CartAddress as LunarCartAddress;

class CartAddress extends LunarCartAddress
{
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CartAddressFactory
    {
        return CartAddressFactory::new();
    }

    /**
     * Check if the cart address has a shipping option .
     */
    public function hasShippingOption(): bool
    {
        return (bool) $this->shipping_option;
    }
}
