<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Modifiers;

use Lunar\Models\Cart;
use Lunar\Models\Contracts\Cart as CartContract;

abstract class PaymentModifier
{
    /**
     * Called just before cart totals are calculated.
     */
    public function handle(CartContract $cart): void
    {
        //
    }
}
