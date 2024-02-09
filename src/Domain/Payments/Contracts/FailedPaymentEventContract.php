<?php

namespace Dystcz\LunarApi\Domain\Payments\Contracts;

use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdapter;
use Lunar\Models\Order;

interface FailedPaymentEventContract
{
    /**
     * Get payment adapter.
     */
    public function getPaymentAdapter(): PaymentAdapter;

    /**
     * Get order.
     */
    public function getOrder(): Order;

    /**
     * Get payment intent.
     */
    public function getPaymentIntent(): PaymentIntent;
}
