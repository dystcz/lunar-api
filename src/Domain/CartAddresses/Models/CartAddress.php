<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Models;

use Dystcz\LunarApi\Domain\CartAddresses\Concerns\InteractsWithLunarApi;
use Lunar\Models\CartAddress as LunarCartAddress;

class CartAddress extends LunarCartAddress
{
    use InteractsWithLunarApi;
}
