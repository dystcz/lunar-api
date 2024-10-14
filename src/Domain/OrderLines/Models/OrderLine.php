<?php

namespace Dystcz\LunarApi\Domain\OrderLines\Models;

use Dystcz\LunarApi\Domain\OrderLines\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\OrderLines\Contracts\OrderLine as OrderLineContract;
use Lunar\Models\OrderLine as LunarOrderLine;

class OrderLine extends LunarOrderLine implements OrderLineContract
{
    use InteractsWithLunarApi;
}
