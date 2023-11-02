<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class OrderRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        return [
            'notes' => [
                'string',
                'nullable',
            ],
            'meta' => [
                'array',
                'nullable',
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
            'notes.string' => __('lunar-api::validations.orders.notes.string'),
            'meta.array' => __('lunar-api::validations.orders.meta.array'),
        ];
    }
}
