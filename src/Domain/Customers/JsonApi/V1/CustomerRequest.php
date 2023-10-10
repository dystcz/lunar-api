<?php

namespace Dystcz\LunarApi\Domain\Customers\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CustomerRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string',
            ],
            'first_name' => [
                'nullable',
                'string',
            ],
            'last_name' => [
                'nullable',
                'string',
            ],
            'company_name' => [
                'nullable',
                'string',
            ],
            'vat_no' => [
                'nullable',
                'string',
            ],
            'account_ref' => [
                'nullable',
                'string',
            ],
        ];
    }
}
