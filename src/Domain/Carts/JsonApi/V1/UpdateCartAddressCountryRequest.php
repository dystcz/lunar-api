<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class UpdateCartAddressCountryRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'cart' => [\LaravelJsonApi\Validation\Rule::toOne(), 'required'],
            'country' => [\LaravelJsonApi\Validation\Rule::toOne(), 'required'],
        ];
    }
}
