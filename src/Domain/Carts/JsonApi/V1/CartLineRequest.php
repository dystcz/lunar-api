<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CartLineRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'quantity' => ['nullable', 'integer'],
            'meta' => ['nullable', 'array'],
            'purchasable_id' => ['required', 'integer'],
            'purchasable_type' => ['required', 'string'],
        ];
    }
}
