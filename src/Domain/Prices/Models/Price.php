<?php

namespace Dystcz\LunarApi\Domain\Prices\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Prices\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Prices\Contracts\Price as PriceContract;
use Lunar\Models\Contracts\Price as LunarPriceContract;
use Lunar\Models\Price as LunarPrice;

#[ReplaceModel(LunarPriceContract::class)]
class Price extends LunarPrice implements PriceContract
{
    use InteractsWithLunarApi;
}
