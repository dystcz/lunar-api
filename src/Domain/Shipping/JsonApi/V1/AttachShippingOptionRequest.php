<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class AttachShippingOptionRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'shipping_option' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        // TODO: Fill in messages
        return [];
    }
}
