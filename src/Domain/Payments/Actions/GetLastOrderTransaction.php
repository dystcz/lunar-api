<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Lunar\Models\Contracts\Order as OrderContract;
use Lunar\Models\Contracts\Transaction as TransactionContract;
use Lunar\Models\Transaction;

class GetLastOrderTransaction
{
    public function __construct(
    ) {}

    /**
     * Get last order transaction.
     */
    public function __invoke(OrderContract $order, string $driver, ?string $type = null): ?TransactionContract
    {
        return Transaction::modelClass()::query()
            ->when($type, fn ($query) => $query->where('type', $type))
            ->where('driver', $driver)
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
