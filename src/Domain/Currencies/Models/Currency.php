<?php

namespace Dystcz\LunarApi\Domain\Currencies\Models;

use Dystcz\LunarApi\Domain\Currencies\Contracts\Currency as CurrencyContract;
use Dystcz\LunarApi\Domain\Currencies\Factories\CurrencyFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Currency as LunarCurrency;

class Currency extends LunarCurrency implements CurrencyContract
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
