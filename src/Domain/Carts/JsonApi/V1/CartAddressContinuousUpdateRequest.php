<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Addresses\Http\Enums\AddressType;
use Illuminate\Validation\Rule;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class CartAddressContinuousUpdateRequest extends CartAddressRequest
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
            'company_in' => [
                'nullable',
                'string',
            ],
            'company_tin' => [
                'nullable',
                'string',
            ],
            'line_one' => [
                'nullable',
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
                'nullable',
                'string',
            ],
            'state' => [
                'nullable',
                'string',
            ],
            'postcode' => [
                'nullable',
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
            'shipping_option' => [
                'nullable',
                'string',
            ],
            'address_type' => [
                'required',
                'string',
                Rule::in([
                    AddressType::SHIPPING->value,
                    AddressType::BILLING->value,
                ]),
            ],

            'cart' => [
                JsonApiRule::toOne(),
                'required',
            ],
            'country' => [
                JsonApiRule::toOne(),
                'nullable',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        // TODO: Fill in messages
        return [];
    }
}
