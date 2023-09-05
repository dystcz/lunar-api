<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Str;

class ApplyCoupon
{
    /**
     * Apply coupon.
     */
    public function __invoke(Cart $cart, string $couponCode): Cart
    {
        $cart->coupon_code = Str::upper($couponCode);

        $cart->save();

        return $cart->calculate();
    }
}
