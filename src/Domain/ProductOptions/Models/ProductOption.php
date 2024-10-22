<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\ProductOptions\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductOptions\Contracts\ProductOption as ProductOptionContract;
use Lunar\Models\Contracts\ProductOption as LunarProductOptionContract;
use Lunar\Models\ProductOption as LunarProductOption;

#[ReplaceModel(LunarProductOptionContract::class)]
class ProductOption extends LunarProductOption implements ProductOptionContract
{
    use InteractsWithLunarApi;
}
