<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\Models;

use Dystcz\LunarApi\Domain\ProductOptions\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductOptions\Contracts\ProductOption as ProductOptionContract;
use Lunar\Models\ProductOption as LunarProductOption;

class ProductOption extends LunarProductOption implements ProductOptionContract
{
    use InteractsWithLunarApi;
}
