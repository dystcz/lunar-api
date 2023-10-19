<?php

namespace Dystcz\LunarApi\Domain\TaxZones\Models;

use Lunar\Models\TaxZone as LunarTaxZone;
use Spatie\LaravelBlink\BlinkFacade;

class TaxZone extends LunarTaxZone
{
    /**
     * Get the default tax percentage.
     *
     * @return self
     */
    public static function getDefaultPercentage()
    {
        $key = 'lunar_default_tax_zone_percentage';

        return BlinkFacade::once($key, function () {
            return floatval(TaxZone::getDefault()->taxAmounts->first()?->percentage);
        });
    }
}
