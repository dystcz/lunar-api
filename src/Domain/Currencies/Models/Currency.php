<?php

namespace Dystcz\LunarApi\Domain\Currencies\Models;

use Dystcz\LunarApi\Domain\Currencies\Factories\CurrencyFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Currency as LunarCurrency;

class Currency extends LunarCurrency
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CurrencyFactory
    {
        return CurrencyFactory::new();
    }
}
