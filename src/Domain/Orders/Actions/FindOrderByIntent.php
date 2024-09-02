<?php

namespace Dystcz\LunarApi\Domain\Orders\Actions;

use Dystcz\LunarApi\Domain\Payments\Data\PaymentIntent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lunar\Models\Order;

class FindOrderByIntent
{
    /**
     * Find order by intent.
     *
     * @throws ModelNotFoundException
     */
    public function __invoke(PaymentIntent $intent): ?Order
    {
        return Order::modelClass()::query()
            ->where('meta->payment_intent', $intent->getId())
            ->firstOrFail();
    }
}
