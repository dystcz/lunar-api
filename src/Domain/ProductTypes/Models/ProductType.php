<?php

namespace Dystcz\LunarApi\Domain\ProductTypes\Models;

use Dystcz\LunarApi\Domain\ProductTypes\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductTypes\Contracts\ProductType as ProductTypeContract;
use Lunar\Models\ProductType as LunarProductType;

class ProductType extends LunarProductType implements ProductTypeContract
{
    use InteractsWithLunarApi;
}
