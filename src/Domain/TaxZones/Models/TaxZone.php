<?php

namespace Dystcz\LunarApi\Domain\TaxZones\Models;

use Dystcz\LunarApi\Domain\TaxZones\Concerns\InteractsWithLunarApi;
use Lunar\Models\TaxZone as LunarTaxZone;

class TaxZone extends LunarTaxZone
{
    use InteractsWithLunarApi;
}
