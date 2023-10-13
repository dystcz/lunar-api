<?php

namespace Dystcz\LunarApi\Domain\Payments\Listeners;

use Dystcz\LunarApi\Domain\Orders\Actions\ChangeOrderStatus;
use Dystcz\LunarApi\Domain\Orders\Enums\OrderStatus;
use Dystcz\LunarApi\Domain\Payments\Contracts\FailedPaymentEventContract;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;

class HandleFailedPayment
{
    public function __construct(
        protected PaymentAdaptersRegister $register
    ) {
    }

    /**
     * Handle the event.
     * Create failed transaction.
     */
    public function handle(FailedPaymentEventContract $event): void
    {
        $paymentIntent = $event->getPaymentIntent();
        $paymentAdapter = $event->getPaymentAdapter();
        $order = $event->getOrder();

        // Change status to awaiting payment
        (new ChangeOrderStatus)($order, OrderStatus::AWAITING_PAYMENT);

        // Create failed transaction
        $paymentAdapter->createFailedTransaction($paymentIntent);
    }
}
