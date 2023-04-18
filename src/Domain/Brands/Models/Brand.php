<?php

namespace Dystcz\LunarApi\Domain\Brands\Models;

use Dystcz\LunarApi\Domain\Brands\Factories\BrandFactory;
use Lunar\Models\Brand as LunarBrand;

class Brand extends LunarBrand
{
    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }
}
