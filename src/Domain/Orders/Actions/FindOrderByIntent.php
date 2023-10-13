<?php

namespace Dystcz\LunarApi\Domain\Orders\Actions;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FindOrderByIntent
{
    /**
     * Find order by intent.
     *
     * @throws ModelNotFoundException
     */
    public function __invoke(mixed $intentId): Order
    {
        return Order::query()
            ->whereHas(
                'transactions',
                fn ($query) => $query
                    ->where('type', 'intent')
                    ->where('reference', $intentId),
            )
            ->firstOrFail();
    }
}
