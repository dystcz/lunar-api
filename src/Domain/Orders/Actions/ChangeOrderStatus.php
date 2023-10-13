<?php

namespace Dystcz\LunarApi\Domain\Orders\Actions;

use Dystcz\LunarApi\Domain\Orders\Contracts\OrderStatusContract;
use Dystcz\LunarApi\Domain\Orders\Events\OrderStatusChanged;
use Lunar\Models\Order;

class ChangeOrderStatus
{
    public function __construct(
    ) {
    }

    /**
     * Change order status to pending payment.
     */
    public function __invoke(Order $order, OrderStatusContract $orderStatus): Order
    {
        $order->update([
            'status' => $orderStatus->value,
        ]);

        OrderStatusChanged::dispatch($order);

        return $order;
    }
}
