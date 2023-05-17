<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Lunar\Models\Cart;
use Lunar\Models\Order as LunarOrder;

class Order extends LunarOrder
{
    public function cart(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
