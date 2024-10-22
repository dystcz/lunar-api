<?php

namespace Dystcz\LunarApi\Domain\Brands\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Brands\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Brands\Contracts\Brand as BrandContract;
use Lunar\Models\Brand as LunarBrand;
use Lunar\Models\Contracts\Brand as LunarBrandContract;

#[ReplaceModel(LunarBrandContract::class)]
class Brand extends LunarBrand implements BrandContract
{
    use InteractsWithLunarApi;
}
