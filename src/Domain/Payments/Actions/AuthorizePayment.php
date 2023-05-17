<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Orders\Actions\FindOrderByIntent;
use Dystcz\LunarApi\Domain\Orders\Events\OrderPaid;
use Illuminate\Support\Facades\App;
use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Facades\Payments;
use Stripe\PaymentIntent;

class AuthorizePayment
{
    public function __invoke(PaymentIntent $intent): void
    {
        info('Payment intent succeeded: '.$intent->id);

        $order = App::make(FindOrderByIntent::class)($intent->id);

        if (! $order) {
            report('Order not found for payment intent: '.$intent->id);

            return;
        }

        /** @var PaymentAuthorize $payment */
        $payment = Payments::driver('stripe')
            ->cart($order->cart)
            ->withData([
                'payment_intent_client_secret' => $intent->client_secret,
                'payment_intent' => $intent->id,
            ])
            ->authorize();

        if (! $payment->success) {
            report('Payment failed for order: '.$order->id.' with reason: '.$payment->message);

            return;
        }

        OrderPaid::dispatch($order);
    }
}
