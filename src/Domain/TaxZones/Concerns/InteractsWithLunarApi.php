<?php

namespace Dystcz\LunarApi\Domain\TaxZones\Concerns;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Spatie\LaravelBlink\BlinkFacade;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

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
