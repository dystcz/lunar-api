<?php

namespace Dystcz\LunarApi\Domain\Orders\Actions;

use Dystcz\LunarApi\Domain\Orders\Models\Order;

class FindOrderByIntent
{
    public function __invoke(mixed $intentId): ?Order
    {
        return Order::where('meta->payment_intent', $intentId)->first();
    }
}
