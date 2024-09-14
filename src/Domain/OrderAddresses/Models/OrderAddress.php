<?php

namespace Dystcz\LunarApi\Domain\OrderAddresses\Models;

use Dystcz\LunarApi\Domain\OrderAddresses\Concerns\InteractsWithLunarApi;
use Lunar\Models\OrderAddress as LunarOrderAddress;

class OrderAddress extends LunarOrderAddress
{
    use InteractsWithLunarApi;
}
