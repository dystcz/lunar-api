<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Lunar\Actions\AbstractAction;

class UnsetPaymentOption extends AbstractAction
{
    /**
     * Unset payment option from cart.
     */
    public function execute(
        Cart $cart
    ): self {
        $cart->paymentOption = null;
        $cart->update([
            'payment_option' => null,
        ]);

        return $this;
    }
}
