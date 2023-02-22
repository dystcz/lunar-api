<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Domain\Carts\Factories\CartAddressFactory;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Lunar\Models\Cart as LunarCart;
use Lunar\Models\CartAddress as LunarCartAddress;

class CartAddress extends LunarCartAddress
{
    protected static function newFactory(): CartAddressFactory
    {
        return CartAddressFactory::new();
    }
}
