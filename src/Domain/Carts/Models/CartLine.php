<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\CartLine as LunarCartLine;

class CartLine extends LunarCartLine
{
    use HashesRouteKey;
}
