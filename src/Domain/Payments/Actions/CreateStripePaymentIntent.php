<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Lunar\Facades\Payments;
use Lunar\Models\Cart;
use Lunar\Models\Transaction;
use Lunar\Stripe\Facades\StripeFacade;
use Stripe\PaymentIntent;

class CreateStripePaymentIntent
{
    public function __invoke(Cart $cart): PaymentIntent
    {
        $intent = StripeFacade::createIntent($cart->calculate());

        Payments::driver('stripe')
            ->cart($cart)
            ->withData([
                'payment_intent_client_secret' => $intent->client_secret,
                'payment_intent' => $intent->id,
            ])
            ->authorize();

        Transaction::create([
            'type' => 'intent',
            'order_id' => $cart->order->id,
            'driver' => 'stripe',
            'amount' => $intent->amount,
            'success' => true,
            'reference' => $intent->id,
            'status' => 'intent',
            'card_type' => 'unknown',
        ]);

        return $intent;
    }
}
