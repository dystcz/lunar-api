<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CartLineRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        return [
            'quantity' => [
                'nullable',
                'integer',
            ],
            'purchasable_id' => [
                'required',
                'integer',
            ],
            'purchasable_type' => [
                'required',
                'string',
            ],
            'meta' => [
                'nullable',
                'array',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string,array<int,mixed>>
     */
    public function messages(): array
    {
        return [
            'quality.integer' => __('lunar-api::validations.cart_lines.quantity.integer'),
            'purchasable_id.required' => __('lunar-api::validations.cart_lines.purchasable_id.required'),
            'purchasable_id.integer' => __('lunar-api::validations.cart_lines.purchasable_id.integer'),
            'purchasable_type.required' => __('lunar-api::validations.cart_lines.purchasable_type.required'),
            'purchasable_type.string' => __('lunar-api::validations.cart_lines.purchasable_type.string'),
            'meta.array' => __('lunar-api::validations.cart_lines.meta.array'),
        ];
    }
}
