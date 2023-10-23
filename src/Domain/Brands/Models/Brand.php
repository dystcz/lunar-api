<?php

namespace Dystcz\LunarApi\Domain\Brands\Models;

use Dystcz\LunarApi\Domain\Brands\Factories\BrandFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Brand as LunarBrand;

class Brand extends LunarBrand
{
    use HashesRouteKey;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }
}
