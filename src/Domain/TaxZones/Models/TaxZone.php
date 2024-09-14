<?php

namespace Dystcz\LunarApi\Domain\TaxZones\Models;

use Lunar\Models\TaxZone as LunarTaxZone;
use Spatie\LaravelBlink\BlinkFacade;

class TaxZone extends LunarTaxZone
{
    /**
     * Get the default tax percentage.
     */
    public static function getDefaultPercentage(): float
    {
        $key = 'lunar_default_tax_zone_percentage';

        return BlinkFacade::once($key, function () {
            return floatval(static::getDefault()->taxAmounts->first()?->percentage);
        });
    }
}
