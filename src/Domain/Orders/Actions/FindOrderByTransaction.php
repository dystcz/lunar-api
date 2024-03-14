<?php

namespace Dystcz\LunarApi\Domain\Orders\Actions;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Data\PaymentIntent;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FindOrderByTransaction
{
    /**
     * Find order by intent.
     *
     * @throws ModelNotFoundException
     */
    public function __invoke(PaymentIntent $intent): ?Order
    {
        return Order::query()
            ->whereHas('transactions', fn ($query) => $query->where('reference', $intent->getId()))
            ->firstOrFail();
    }
}
