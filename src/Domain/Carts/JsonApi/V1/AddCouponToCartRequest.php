<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use Lunar\Rules\ValidCoupon;

class AddCouponToCartRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'coupon_code' => [
                'required',
                'string',
                new ValidCoupon,
            ],
        ];
    }
}
