<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Dystcz\LunarApi\Domain\Orders\Factories\OrderFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Lunar\Models\Order as LunarOrder;
use Lunar\Models\Transaction;

class Order extends LunarOrder
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

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
