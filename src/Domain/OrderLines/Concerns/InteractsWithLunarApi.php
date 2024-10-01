<?php

namespace Dystcz\LunarApi\Domain\OrderLines\Concerns;

use Dystcz\LunarApi\Domain\OrderLines\Factories\OrderLineFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): OrderLineFactory
    {
        return OrderLineFactory::new();
    }
}
