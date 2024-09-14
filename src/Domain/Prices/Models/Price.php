<?php

namespace Dystcz\LunarApi\Domain\Prices\Models;

use Dystcz\LunarApi\Domain\Prices\Contracts\Price as PriceContract;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Price as LunarPrice;

class Price extends LunarPrice implements PriceContract
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): PriceFactory
    {
        return PriceFactory::new();
    }
}
