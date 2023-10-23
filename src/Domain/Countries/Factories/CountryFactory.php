<?php

namespace Dystcz\LunarApi\Domain\Countries\Factories;

use Dystcz\LunarApi\Domain\Countries\Models\Country;
use Lunar\Database\Factories\CountryFactory as LunarCountryFactory;

class CountryFactory extends LunarCountryFactory
{
    protected $model = Country::class;
}
