<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Orders\Actions\ChangeOrderStatus;
use Dystcz\LunarApi\Domain\Orders\Enums\OrderStatus;
use Lunar\Models\Order;

class MarkAwaitingPayment
{
    /**
     * Change order status to pending payment.
     */
    public function __invoke(Order $order): Order
    {
        $order = (new ChangeOrderStatus)($order, OrderStatus::AWAITING_PAYMENT);

        return $order;
    }
}
