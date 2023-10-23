<?php

namespace Dystcz\LunarApi\Domain\Countries\Models;

use Dystcz\LunarApi\Domain\Countries\Factories\CountryFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Country as LunarCountry;

class Country extends LunarCountry
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
