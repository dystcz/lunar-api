<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentIntent;
use Lunar\Models\Cart;

class CreatePaymentIntent
{
    public function __construct(
        protected PaymentAdaptersRegister $register
    ) {
    }

    /**
     * Create payment intent.
     *
     * @param  array<string,mixed>  $meta
     */
    public function __invoke(string $paymentMethod, Cart $cart, array $meta = []): PaymentIntent
    {
        $payment = $this->register->get($paymentMethod);

        $intent = $payment->createIntent($cart, $meta);

        return $intent;
    }
}
