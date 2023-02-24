<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class OrderRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'notes' => 'string|nullable',
        ];
    }
}
