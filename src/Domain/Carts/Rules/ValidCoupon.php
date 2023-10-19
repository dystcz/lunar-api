<?php

namespace Dystcz\LunarApi\Domain\Carts\Rules;

use Lunar\Rules\ValidCoupon as LunarValidCoupon;

class ValidCoupon extends LunarValidCoupon
{
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string|array
    {
        return __('lunar-api::validations.carts.coupon_code.invalid');
    }
}
