<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Rules\ValidCoupon;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class SetCouponToCartRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
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

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            'coupon_code.required' => __('lunar-api::validations.carts.coupon_code.required'),
            'coupon_code.string' => __('lunar-api::validations.carts.coupon_code.string'),
        ];
    }
}
