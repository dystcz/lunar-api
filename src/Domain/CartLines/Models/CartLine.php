<?php

namespace Dystcz\LunarApi\Domain\CartLines\Models;

use Dystcz\LunarApi\Domain\CartLines\Contracts\CartLine as CartLineContract;
use Dystcz\LunarApi\Domain\CartLines\Factories\CartLineFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\CartLine as LunarCartLine;

class CartLine extends LunarCartLine implements CartLineContract
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
