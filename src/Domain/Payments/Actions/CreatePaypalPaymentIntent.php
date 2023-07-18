<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarPaypal\Data\Order as PaypalOrder;
use Dystcz\LunarPaypal\Facades\PaypalFacade;
use Lunar\Models\Cart;
use Lunar\Models\Transaction;

class CreatePaypalPaymentIntent
{
    public function __invoke(Cart $cart): PaypalOrder
    {
        /** @var PaypalOrder $intent */
        $intent = PaypalFacade::createIntent($cart->calculate());

        Transaction::create([
            'type' => 'intent',
            'order_id' => $cart->order->id,
            'driver' => 'paypal',
            'amount' => $intent->totalAmount(),
            'success' => true,
            'reference' => $intent->id,
            'status' => 'intent',
            'card_type' => 'paypal',
        ]);

        return $intent;
    }
}
