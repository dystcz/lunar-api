<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\Models;

use Dystcz\LunarApi\Domain\ProductOptions\Factories\ProductOptionFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\ProductOption as LunarProductOption;

class ProductOption extends LunarProductOption
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductOptionFactory
    {
        return ProductOptionFactory::new();
    }
}
