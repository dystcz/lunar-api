<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class DetachShippingOptionRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
