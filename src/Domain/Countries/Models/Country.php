<?php

namespace Dystcz\LunarApi\Domain\Countries\Models;

use Dystcz\LunarApi\Domain\Countries\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Countries\Contracts\Country as CountryContract;
use Lunar\Models\Country as LunarCountry;

class Country extends LunarCountry implements CountryContract
{
    use InteractsWithLunarApi;
}
