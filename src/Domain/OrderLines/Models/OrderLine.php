<?php

namespace Dystcz\LunarApi\Domain\OrderLines\Models;

use Dystcz\LunarApi\Domain\OrderLines\Factories\OrderLineFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\OrderLine as LunarOrderLine;

class OrderLine extends LunarOrderLine
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
