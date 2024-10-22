<?php

namespace Dystcz\LunarApi\Domain\Products\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Products\Builders\ProductBuilder;
use Dystcz\LunarApi\Domain\Products\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Products\Contracts\Product as ProductContract;
use Lunar\Models\Contracts\Product as LunarProductContract;
use Lunar\Models\Product as LunarProduct;

/**
 * @method static ProductBuilder query()
 */
#[ReplaceModel(LunarProductContract::class)]
class Product extends LunarProduct implements ProductContract
{
    use InteractsWithLunarApi;
}
