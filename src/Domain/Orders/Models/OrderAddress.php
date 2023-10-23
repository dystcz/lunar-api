<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\OrderAddress as LunarOrderAddress;

class OrderAddress extends LunarOrderAddress
{
    use HashesRouteKey;
}
