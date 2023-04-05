<?php

namespace Dystcz\LunarApi\Domain\Prices\Models;

use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Lunar\Models\Price as LunarPrice;

class Price extends LunarPrice
{
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): PriceFactory
    {
        return PriceFactory::new();
    }
}
