<?php

namespace Dystcz\LunarApi\Domain\Products\Models;

use Dystcz\LunarApi\Domain\Products\Builders\ProductBuilder;
use Dystcz\LunarApi\Domain\Products\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Products\Contracts\Product as ProductContract;
use Lunar\Models\Product as LunarProduct;

/**
 * @method static ProductBuilder query()
 */
class Product extends LunarProduct implements ProductContract
{
    use InteractsWithLunarApi;
}
