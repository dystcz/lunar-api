<?php

namespace Dystcz\LunarApi\Domain\Products\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\ProductOption as LunarProductOption;

class ProductOption extends LunarProductOption
{
    use HashesRouteKey;
}
