<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Lunar\Actions\AbstractAction;
use Lunar\DataTypes\ShippingOption;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;

class SetPaymentOption extends AbstractAction
{
    /**
     * Execute the action.
     *
     * @param  CartLine  $cartLine
     * @param  ShippingOption  $customerGroups
     */
    public function execute(
        Cart $cart,
        PaymentOption $paymentOption
    ): self {
        $cart->update([
            'payment_option' => $paymentOption->getIdentifier(),
        ]);

        return $this;
    }
}
