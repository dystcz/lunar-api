<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Str;
use Lunar\Models\Contracts\Cart as CartContract;

class SetCoupon
{
    /**
     * Apply coupon.
     */
    public function __invoke(CartContract $cart, string $couponCode): Cart
    {
        /** @var Cart $cart */
        $cart->coupon_code = Str::upper($couponCode);

        $cart->save();

        return $cart->calculate();
    }
}
