<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;

class ApplyCoupon
{
    /**
     * Apply coupon.
     */
    public function __invoke(Cart $cart, string $couponCode): Cart
    {
        $cart->coupon_code = $couponCode;

        $cart->save();

        return $cart->calculate();
    }
}
