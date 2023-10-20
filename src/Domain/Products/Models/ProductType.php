<?php

namespace Dystcz\LunarApi\Domain\Products\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\ProductType as LunarProductType;

class ProductType extends LunarProductType
{
    use HashesRouteKey;
}
