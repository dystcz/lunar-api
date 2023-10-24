<?php

namespace Dystcz\LunarApi\Domain\ProductTypes\Models;

use Dystcz\LunarApi\Domain\ProductTypes\Factories\ProductTypeFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\ProductType as LunarProductType;

class ProductType extends LunarProductType
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
