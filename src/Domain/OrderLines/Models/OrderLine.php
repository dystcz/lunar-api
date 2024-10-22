<?php

namespace Dystcz\LunarApi\Domain\OrderLines\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\OrderLines\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\OrderLines\Contracts\OrderLine as OrderLineContract;
use Lunar\Models\Contracts\OrderLine as LunarOrderLineContract;
use Lunar\Models\OrderLine as LunarOrderLine;

#[ReplaceModel(LunarOrderLineContract::class)]
class OrderLine extends LunarOrderLine implements OrderLineContract
{
    use InteractsWithLunarApi;
}
