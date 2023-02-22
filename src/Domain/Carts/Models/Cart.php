<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Lunar\Models\Cart as LunarCart;

class Cart extends LunarCart
{
    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }
}
