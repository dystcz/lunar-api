<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class UnsetShippingOptionRequest extends ResourceRequest
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
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            //
        ];
    }
}
