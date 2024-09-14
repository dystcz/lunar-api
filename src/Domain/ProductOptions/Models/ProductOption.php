<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\Models;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Dystcz\LunarApi\Domain\ProductOptions\Concerns\InteractsWithLunarApi;
use Lunar\Models\ProductOption as LunarProductOption;

class ProductOption extends LunarProductOption implements Translatable
{
    use InteractsWithLunarApi;
}
