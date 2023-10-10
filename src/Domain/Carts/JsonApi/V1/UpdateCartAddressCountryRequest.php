<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule;

class UpdateCartAddressCountryRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array>
     */
    public function rules(): array
    {
        return [
            'cart' => [
                Rule::toOne(),
                'required',
            ],
            'country' => [
                Rule::toOne(),
                'required',
            ],
        ];
    }
}
