<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\CartAddresses\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\CartAddresses\Contracts\CartAddress as CartAddressContract;
use Lunar\Models\CartAddress as LunarCartAddress;
use Lunar\Models\Contracts\CartAddress as LunarCartAddressContract;

#[ReplaceModel(LunarCartAddressContract::class)]
class CartAddress extends LunarCartAddress implements CartAddressContract
{
    use InteractsWithLunarApi;
}
