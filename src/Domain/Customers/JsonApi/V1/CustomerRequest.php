<?php

namespace Dystcz\LunarApi\Domain\Customers\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CustomerRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
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

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            'title.string' => __('lunar-api::validations.customers.title.string'),
            'first_name.string' => __('lunar-api::validations.customers.first_name.string'),
            'last_name.string' => __('lunar-api::validations.customers.last_name.string'),
            'company_name.string' => __('lunar-api::validations.customers.company_name.string'),
            'vat_no.string' => __('lunar-api::validations.customers.vat_no.string'),
            'account_ref.string' => __('lunar-api::validations.customers.account_ref.string'),
        ];
    }
}
