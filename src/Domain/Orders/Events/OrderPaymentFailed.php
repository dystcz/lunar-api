<?php

namespace Dystcz\LunarApi\Domain\Orders\Events;

use Dystcz\LunarApi\Domain\Payments\Contracts\FailedPaymentEventContract;
use Dystcz\LunarApi\Domain\Payments\Data\PaymentIntent;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdapter;
use Illuminate\Foundation\Events\Dispatchable;
use Lunar\Models\Order;

class OrderPaymentFailed implements FailedPaymentEventContract
{
    use Dispatchable;

    public function __construct(
        public Order $order,
        public PaymentAdapter $paymentAdapter,
        public PaymentIntent $paymentIntent,
    ) {
    }

    /**
     * Get payment adapter.
     */
    public function getPaymentAdapter(): PaymentAdapter
    {
        return $this->paymentAdapter;
    }

    /**
     * Get order.
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * Get payment intent.
     */
    public function getPaymentIntent(): PaymentIntent
    {
        return $this->paymentIntent;
    }
}
