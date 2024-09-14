<?php

namespace Dystcz\LunarApi\Domain\Products\Models;

use Dystcz\LunarApi\Base\Contracts\HasAvailability;
use Dystcz\LunarApi\Base\Contracts\Translatable;
use Dystcz\LunarApi\Domain\Products\Builders\ProductBuilder;
use Dystcz\LunarApi\Domain\Products\Concerns\InteractsWithLunarApi;
use Lunar\Models\Product as LunarProduct;

/**
 * @method static ProductBuilder query()
 */
class Product extends LunarProduct implements HasAvailability, Translatable
{
    use InteractsWithLunarApi;
}
