<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CartRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'create_user' => 'boolean',
            'meta' => 'array',
        ];
    }
}
