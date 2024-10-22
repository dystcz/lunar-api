<?php

namespace Dystcz\LunarApi\Domain\ProductTypes\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\ProductTypes\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductTypes\Contracts\ProductType as ProductTypeContract;
use Lunar\Models\Contracts\ProductType as LunarProductTypeContract;
use Lunar\Models\ProductType as LunarProductType;

#[ReplaceModel(LunarProductTypeContract::class)]
class ProductType extends LunarProductType implements ProductTypeContract
{
    use InteractsWithLunarApi;
}
