<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Lunar\Models\Cart;
use Lunar\Models\Transaction;
use Lunar\Stripe\Facades\StripeFacade;
use Stripe\PaymentIntent;

class CreateStripePaymentIntent
{
    public function __invoke(Cart $cart): PaymentIntent
    {
        $intent = StripeFacade::createIntent($cart->calculate());

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
