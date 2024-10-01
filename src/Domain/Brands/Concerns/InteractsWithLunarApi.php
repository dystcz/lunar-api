<?php

namespace Dystcz\LunarApi\Domain\Brands\Concerns;

use Dystcz\LunarApi\Domain\Brands\Factories\BrandFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
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
