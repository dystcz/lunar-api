<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\ProductOptionValues\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductOptionValues\Contracts\ProductOptionValue as ProductOptionValueContract;
use Lunar\Models\Contracts\ProductOptionValue as LunarProductOptionValueContract;
use Lunar\Models\ProductOptionValue as LunarProductOptionValue;

#[ReplaceModel(LunarProductOptionValueContract::class)]
class ProductOptionValue extends LunarProductOptionValue implements ProductOptionValueContract
{
    use InteractsWithLunarApi;
}
