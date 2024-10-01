<?php

namespace Dystcz\LunarApi\Domain\Brands\Models;

use Dystcz\LunarApi\Domain\Brands\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Brands\Contracts\Brand as BrandContract;
use Lunar\Models\Brand as LunarBrand;

class Brand extends LunarBrand implements BrandContract
{
    use InteractsWithLunarApi;
}
