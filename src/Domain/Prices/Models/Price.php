<?php

namespace Dystcz\LunarApi\Domain\Prices\Models;

use Dystcz\LunarApi\Domain\Prices\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Prices\Contracts\Price as PriceContract;
use Lunar\Models\Price as LunarPrice;

class Price extends LunarPrice implements PriceContract
{
    use InteractsWithLunarApi;
}
