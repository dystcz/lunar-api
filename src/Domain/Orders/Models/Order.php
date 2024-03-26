<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Dystcz\LunarApi\Domain\Orders\Factories\OrderFactory;
use Dystcz\LunarApi\Domain\PaymentOptions\Casts\PaymentBreakdown;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Config;
use Lunar\Base\Casts\Price;
use Lunar\Models\Order as LunarOrder;
use Lunar\Models\Transaction;

/**
 * @property int $payment_total
 * @property array $payment_breakdown
 */
class Order extends LunarOrder
{
    use HashesRouteKey;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->casts = array_merge($this->casts, [
            'payment_total' => Price::class,
            'payment_breakdown' => PaymentBreakdown::class,
        ]);
    }

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
        return $this->lines()->whereNotIn(
            'type',
            Config::get('lunar-api.general.purchasable.non_eloquent_types', []),
        );
    }

    /**
     * Return payment lines relationship.
     */
    public function paymentLines(): HasMany
    {
        return $this->lines()->where('type', 'payment');
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
