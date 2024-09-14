<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Orders\Actions\ChangeOrderStatus;
use Dystcz\LunarApi\Domain\Orders\Enums\OrderStatus;
use Lunar\Models\Contracts\Order as OrderContract;

class MarkPendingPayment
{
    /**
     * Change order status to pending payment.
     */
    public function __invoke(OrderContract $order): OrderContract
    {
        $order = (new ChangeOrderStatus)($order, OrderStatus::PENDING_PAYMENT);

        return $order;
    }
}
