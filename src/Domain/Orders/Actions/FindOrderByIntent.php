<?php

namespace Dystcz\LunarApi\Domain\Orders\Actions;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentIntent;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FindOrderByIntent
{
    /**
     * Find order by intent.
     *
     * @throws ModelNotFoundException
     */
    public function __invoke(PaymentIntent $intent): Order
    {
        return Order::query()
            ->whereHas(
                'transactions',
                fn ($query) => $query
                    ->where('type', 'intent')
                    ->where('reference', $intent->id),
            )
            ->firstOrFail();
    }
}
