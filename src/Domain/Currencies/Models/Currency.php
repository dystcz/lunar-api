<?php

namespace Dystcz\LunarApi\Domain\Currencies\Models;

use Dystcz\LunarApi\Domain\Currencies\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Currencies\Contracts\Currency as CurrencyContract;
use Lunar\Models\Currency as LunarCurrency;

class Currency extends LunarCurrency implements CurrencyContract
{
    use InteractsWithLunarApi;
}
