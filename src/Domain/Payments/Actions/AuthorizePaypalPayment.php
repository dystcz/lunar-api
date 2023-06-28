<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Orders\Events\OrderPaid;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Facades\Payments;

class AuthorizePaypalPayment
{
    public function __invoke(Order $order): void
    {
        info('Authorize paypal payment for order: '.$order->id);

        /** @var PaymentAuthorize $payment */
        $payment = Payments::driver('paypal')
            ->cart($order->cart)
            ->withData([
                'payment_intent' => $order->meta->payment_intent,
            ])
            ->authorize();

        if (! $payment->success) {
            report('Payment failed for order: '.$order->id.' with reason: '.$payment->message);

            return;
        }

        OrderPaid::dispatch($order);
    }
}
