<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;

class UnsetCoupon
{
    /**
     * Apply coupon.
     */
    public function __invoke(Cart $cart): Cart
    {
        $cart->coupon_code = null;
        $cart->save();

        return $cart->calculate();
    }
}
