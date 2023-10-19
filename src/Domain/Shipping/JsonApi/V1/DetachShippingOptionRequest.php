<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class DetachShippingOptionRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        return [
            //
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
            //
        ];
    }
}
