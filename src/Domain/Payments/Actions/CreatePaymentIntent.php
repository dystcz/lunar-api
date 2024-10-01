<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Dystcz\LunarApi\Support\Actions\Action;
use Lunar\Models\Contracts\Cart as CartContract;

class CreatePaymentIntent extends Action
{
    public function __construct(
        protected PaymentAdaptersRegister $register
    ) {}

    /**
     * Create payment intent.
     *
     * @param  array<string,mixed>  $meta
     */
    public function handle(string $paymentMethod, CartContract $cart, array $meta = [], ?int $amount = null): PaymentIntent
    {
        $payment = $this->register->get($paymentMethod);

        $intent = $payment->createIntent($cart, $meta, $amount);

        return $intent;
    }
}
