<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Lunar\Actions\AbstractAction;

class SetPaymentOption extends AbstractAction
{
    /**
     * Set payment option for the cart.
     */
    public function execute(
        Cart $cart,
        PaymentOption $paymentOption
    ): self {
        $cart->paymentOption = $paymentOption;
        $cart->update([
            'payment_option' => $paymentOption->getIdentifier(),
        ]);

        return $this;
    }
}
