<?php

namespace Dystcz\LunarApi\Domain\Currencies\Concerns;

use Dystcz\LunarApi\Domain\Currencies\Factories\CurrencyFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
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
