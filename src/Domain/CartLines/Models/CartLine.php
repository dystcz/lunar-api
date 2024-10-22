<?php

namespace Dystcz\LunarApi\Domain\CartLines\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\CartLines\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\CartLines\Contracts\CartLine as CartLineContract;
use Lunar\Models\CartLine as LunarCartLine;
use Lunar\Models\Contracts\CartLine as LunarCartLineContract;

#[ReplaceModel(LunarCartLineContract::class)]
class CartLine extends LunarCartLine implements CartLineContract
{
    use InteractsWithLunarApi;
}
