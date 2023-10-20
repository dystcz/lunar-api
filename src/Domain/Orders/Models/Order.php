<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Lunar\Models\Order as LunarOrder;
use Lunar\Models\Transaction;

class Order extends LunarOrder
{
    use HashesRouteKey;

    /**
     * Get the latest transaction for the order.
     */
    public function latestTransaction(): HasOne
    {
        return $this
            ->hasOne(Transaction::class)
            ->latestOfMany();
    }
}
