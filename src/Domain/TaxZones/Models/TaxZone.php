<?php

namespace Dystcz\LunarApi\Domain\TaxZones\Models;

use Dystcz\LunarApi\Domain\TaxZones\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\TaxZones\Contracts\TaxZone as TaxZoneContract;
use Lunar\Models\TaxZone as LunarTaxZone;

class TaxZone extends LunarTaxZone implements TaxZoneContract
{
    use InteractsWithLunarApi;
}
