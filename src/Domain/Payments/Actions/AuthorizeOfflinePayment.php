<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentSuccessful;
use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Facades\Payments;
use Lunar\Models\Cart;
use Lunar\Models\Order;

class AuthorizeOfflinePayment
{
    /**
     * @param  array<string,mixed>  $meta
     */
    public function __invoke(Order $order, Cart $cart, ?array $meta = null): void
    {
        /** @var PaymentAuthorize $payment */
        $payment = Payments::driver('offline')
            ->order($order)
            ->cart($cart)
            ->withData([
                'meta' => $meta,
            ])
            ->authorize();

        if (! $payment->success) {
            report("Payment failed for order: {$order->id} with reason: {$payment->message}");

            return;
        }

        OrderPaymentSuccessful::dispatch($order);
    }
}
