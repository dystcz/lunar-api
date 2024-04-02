<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class SetPaymentOptionRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        return [
            'payment_option' => [
                'required',
                'string',
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
            'payment_option.required' => __('lunar-api::validations.payments.set_payment_option.payment_option.required'),
            'payment_option.string' => __('lunar-api::validations.payments.set_payment_option.payment_option.string'),
        ];
    }
}
