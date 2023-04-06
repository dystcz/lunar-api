<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Lunar\Models\Cart;

class ApplyCoupon
{
    /**
     * Apply coupon.
     */
    public function __invoke(Cart $cart, string $couponCode): Cart
    {
        $cart->coupon_code = $couponCode;

        $cart->calculate();

        $cart->saveQuietly();

        return $cart;
    }
}
