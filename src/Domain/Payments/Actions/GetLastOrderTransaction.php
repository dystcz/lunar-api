<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Lunar\Models\Order;
use Lunar\Models\Transaction;

class GetLastOrderTransaction
{
    public function __construct(
    ) {
    }

    /**
     * Get last order transaction.
     */
    public function __invoke(Order $order, string $driver, string $type = null): ?Transaction
    {
        return Transaction::query()
            ->when($type, fn ($query) => $query->where('type', $type))
            ->where('driver', $driver)
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
