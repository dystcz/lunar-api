<?php

namespace Dystcz\LunarApi\Domain\Orders\Actions;

use Dystcz\LunarApi\Domain\Orders\Contracts\OrderStatusContract;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Lunar\Models\Contracts\Order as OrderContract;

class ChangeOrderStatus
{
    public function __construct(
    ) {}

    /**
     * Change order status to pending payment.
     */
    public function __invoke(OrderContract $order, OrderStatusContract|string $orderStatus): Order
    {
        /** @var Order $order */
        $newStatus = $orderStatus instanceof OrderStatusContract
            ? $orderStatus->value
            : $orderStatus;

        $oldStatus = $order->status;

        // Return early if status is not changed
        if ($oldStatus === $newStatus) {
            return $order;
        }

        $order->update(['status' => $newStatus]);

        return $order;
    }
}
