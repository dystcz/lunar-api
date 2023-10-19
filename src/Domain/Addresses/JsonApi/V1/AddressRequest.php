<?php

namespace Dystcz\LunarApi\Domain\Addresses\JsonApi\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class AddressRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string',
            ],
            'first_name' => [
                'required',
                'string',
            ],
            'last_name' => [
                'required',
                'string',
            ],
            'company_name' => [
                'nullable',
                'string',
            ],
            'company_in' => [
                'nullable',
                'string',
            ],
            'company_tin' => [
                'nullable',
                'string',
            ],
            'line_one' => [
                'required',
                'string',
            ],
            'line_two' => [
                'nullable',
                'string',
            ],
            'line_three' => [
                'nullable',
                'string',
            ],
            'city' => [
                'required',
                'string',
            ],
            'state' => [
                'nullable',
                'string',
            ],
            'postcode' => [
                'required',
                'string',
            ],
            'delivery_instructions' => [
                'nullable',
                'string',
            ],
            'contact_email' => [
                'nullable',
                'string',
            ],
            'contact_phone' => [
                'nullable',
                'string',
            ],
            'shipping_default' => [
                'nullable',
                'boolean',
            ],
            'billing_default' => [
                'nullable',
                'boolean',
            ],
            'meta' => [
                'nullable',
                'array',
            ],

            'customer' => [
                JsonApiRule::toOne(),
                'required',
            ],
            'country' => [
                JsonApiRule::toOne(),
                'required',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.string' => __('lunar-api::validations.addresses.title.string'),
            'first_name.required' => __('lunar-api::validations.addresses.first_name.required'),
            'first_name.string' => __('lunar-api::validations.addresses.first_name.string'),
            'last_name.required' => __('lunar-api::validations.addresses.last_name.required'),
            'last_name.string' => __('lunar-api::validations.addresses.last_name.string'),
            'company_name.string' => __('lunar-api::validations.addresses.company_name.string'),
            'company_in.string' => __('lunar-api::validations.addresses.company_in.string'),
            'company_tin.string' => __('lunar-api::validations.addresses.company_tin.string'),
            'line_one.required' => __('lunar-api::validations.addresses.line_one.required'),
            'line_one.string' => __('lunar-api::validations.addresses.line_one.string'),
            'line_two.string' => __('lunar-api::validations.addresses.line_two.string'),
            'line_three.string' => __('lunar-api::validations.addresses.line_three.string'),
            'city.required' => __('lunar-api::validations.addresses.city.required'),
            'city.string' => __('lunar-api::validations.addresses.city.string'),
            'state.string' => __('lunar-api::validations.addresses.state.string'),
            'postcode.required' => __('lunar-api::validations.addresses.postcode.required'),
            'postcode.string' => __('lunar-api::validations.addresses.postcode.string'),
            'delivery_instructions.string' => __('lunar-api::validations.addresses.delivery_instructions.string'),
            'contact_email.string' => __('lunar-api::validations.addresses.contact_email.string'),
            'contact_phone.string' => __('lunar-api::validations.addresses.contact_phone.string'),
            'shipping_default.boolean' => __('lunar-api::validations.addresses.shipping_default.boolean'),
            'billing_default.boolean' => __('lunar-api::validations.addresses.billing_default.boolean'),
            'meta.array' => __('lunar-api::validations.addresses.meta.array'),
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $newCustomerId = (int) $this->input('data.relationships.customer.data.id');

            if (! $newCustomerId) {
                return;
            }

            if (! Auth::user()->customers->pluck('id')->contains($newCustomerId)) {
                $validator->errors()->add(
                    'customer',
                    'This customer does not belong to the authenticated user.'
                );
            }
        });
    }
}
