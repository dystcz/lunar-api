<?php

namespace Dystcz\LunarApi\Domain\OrderAddresses\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\OrderAddresses\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\OrderAddresses\Contracts\OrderAddress as OrderAddressContract;
use Lunar\Models\Contracts\OrderAddress as LunarOrderAddressContract;
use Lunar\Models\OrderAddress as LunarOrderAddress;

#[ReplaceModel(LunarOrderAddressContract::class)]
class OrderAddress extends LunarOrderAddress implements OrderAddressContract
{
    use InteractsWithLunarApi;
}
