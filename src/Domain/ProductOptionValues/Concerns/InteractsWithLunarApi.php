<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\Concerns;

use Dystcz\LunarApi\Domain\ProductOptionValues\Factories\ProductOptionValueFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductOptionValueFactory
    {
        return ProductOptionValueFactory::new();
    }
}
