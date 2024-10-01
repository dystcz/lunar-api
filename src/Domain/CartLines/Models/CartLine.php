<?php

namespace Dystcz\LunarApi\Domain\CartLines\Models;

use Dystcz\LunarApi\Domain\CartLines\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\CartLines\Contracts\CartLine as CartLineContract;
use Lunar\Models\CartLine as LunarCartLine;

class CartLine extends LunarCartLine implements CartLineContract
{
    use InteractsWithLunarApi;
}
