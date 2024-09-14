<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Lunar\Models\Contracts\Cart as CartContract;

class UnsetCoupon
{
    /**
     * Apply coupon.
     */
    public function __invoke(CartContract $cart): CartContract
    {
        /** @var Cart $cart */
        $cart->coupon_code = null;
        $cart->save();

        return $cart->calculate();
    }
}
