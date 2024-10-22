<?php

namespace Dystcz\LunarApi\Domain\Countries\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Countries\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Countries\Contracts\Country as CountryContract;
use Lunar\Models\Contracts\Country as LunarCountryContract;
use Lunar\Models\Country as LunarCountry;

#[ReplaceModel(LunarCountryContract::class)]
class Country extends LunarCountry implements CountryContract
{
    use InteractsWithLunarApi;
}
