<?php

namespace Dystcz\LunarApi\Domain\Countries\Concerns;

use Dystcz\LunarApi\Domain\Countries\Factories\CountryFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
