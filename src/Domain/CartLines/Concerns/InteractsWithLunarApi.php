<?php

namespace Dystcz\LunarApi\Domain\CartLines\Concerns;

use Dystcz\LunarApi\Domain\CartLines\Factories\CartLineFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
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
