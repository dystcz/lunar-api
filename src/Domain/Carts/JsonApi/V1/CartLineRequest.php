<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CartLineRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
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
     */
    public function messages(): array
    {
        // TODO: Fill in messages
        return [];
    }
}
