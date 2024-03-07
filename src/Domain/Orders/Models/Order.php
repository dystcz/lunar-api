<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Dystcz\LunarApi\Domain\Orders\Factories\OrderFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Config;
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
     * Return product lines relationship.
     */
    public function productLines(): HasMany
    {
        return $this
            ->lines()
            ->whereNotIn(
                'type',
                Config::get('lunar-api.general.purchasable.non_eloquent_types', []),
            );
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
