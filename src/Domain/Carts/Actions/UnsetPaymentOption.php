<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Support\Actions\Action;

class UnsetPaymentOption extends Action
{
    /**
     * Unset payment option from cart.
     */
    public function handle(
        Cart $cart
    ): self {
        $cart->paymentOption = null;
        $cart->update([
            'payment_option' => null,
        ]);

        return $this;
    }
}
