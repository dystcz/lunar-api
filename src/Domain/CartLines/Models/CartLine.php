<?php

namespace Dystcz\LunarApi\Domain\CartLines\Models;

use Dystcz\LunarApi\Domain\CartLines\Factories\CartLineFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\CartLine as LunarCartLine;

class CartLine extends LunarCartLine
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CartLineFactory
    {
        return CartLineFactory::new();
    }
}
