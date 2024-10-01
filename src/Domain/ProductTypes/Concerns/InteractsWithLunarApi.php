<?php

namespace Dystcz\LunarApi\Domain\ProductTypes\Concerns;

use Dystcz\LunarApi\Domain\ProductTypes\Factories\ProductTypeFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductTypeFactory
    {
        return ProductTypeFactory::new();
    }
}
