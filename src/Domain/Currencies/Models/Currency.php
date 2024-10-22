<?php

namespace Dystcz\LunarApi\Domain\Currencies\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Currencies\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Currencies\Contracts\Currency as CurrencyContract;
use Lunar\Models\Contracts\Currency as LunarCurrencyContract;
use Lunar\Models\Currency as LunarCurrency;

#[ReplaceModel(LunarCurrencyContract::class)]
class Currency extends LunarCurrency implements CurrencyContract
{
    use InteractsWithLunarApi;
}
