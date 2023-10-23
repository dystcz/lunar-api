<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\OrderLine as LunarOrderLine;

class OrderLine extends LunarOrderLine
{
    use HashesRouteKey;
}
