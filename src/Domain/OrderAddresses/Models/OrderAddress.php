<?php

namespace Dystcz\LunarApi\Domain\OrderAddresses\Models;

use Dystcz\LunarApi\Domain\OrderAddresses\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\OrderAddresses\Contracts\OrderAddress as OrderAddressContract;
use Lunar\Models\OrderAddress as LunarOrderAddress;

class OrderAddress extends LunarOrderAddress implements OrderAddressContract
{
    use InteractsWithLunarApi;
}
