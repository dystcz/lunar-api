<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Domain\Carts\Factories\CartAddressFactory;
use Lunar\Models\CartAddress as LunarCartAddress;

class CartAddress extends LunarCartAddress
{
    protected static function newFactory(): CartAddressFactory
    {
        return CartAddressFactory::new();
    }
}
