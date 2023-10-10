<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class OrderRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'notes' => [
                'string',
                'nullable',
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
