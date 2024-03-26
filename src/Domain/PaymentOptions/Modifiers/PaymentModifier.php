<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Modifiers;

use Lunar\Models\Cart;

abstract class PaymentModifier
{
    /**
     * Called just before cart totals are calculated.
     */
    public function handle(Cart $cart): void
    {
        //
    }
}
