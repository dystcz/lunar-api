<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\Models;

use Dystcz\LunarApi\Domain\ProductOptionValues\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductOptionValues\Contracts\ProductOptionValue as ProductOptionValueContract;
use Lunar\Models\ProductOptionValue as LunarProductOptionValue;

class ProductOptionValue extends LunarProductOptionValue implements ProductOptionValueContract
{
    use InteractsWithLunarApi;
}
