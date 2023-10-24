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
     *
     * @return array<string,array<int,mixed>>
     */
    public function messages(): array
    {
        return [
            'title.string' => __('lunar-api::validation.cart_addresses.title.string'),
            'first_name.string' => __('lunar-api::validation.cart_addresses.first_name.string'),
            'last_name.string' => __('lunar-api::validation.cart_addresses.last_name.string'),
            'company_name.string' => __('lunar-api::validation.cart_addresses.company_name.string'),
            'company_in.string' => __('lunar-api::validation.cart_addresses.company_in.string'),
            'company_tin.string' => __('lunar-api::validation.cart_addresses.company_tin.string'),
            'line_one.string' => __('lunar-api::validation.cart_addresses.line_one.string'),
            'line_two.string' => __('lunar-api::validation.cart_addresses.line_two.string'),
            'line_three.string' => __('lunar-api::validation.cart_addresses.line_three.string'),
            'city.string' => __('lunar-api::validation.cart_addresses.city.string'),
            'state.string' => __('lunar-api::validation.cart_addresses.state.string'),
            'postcode.string' => __('lunar-api::validation.cart_addresses.postcode.string'),
            'delivery_instructions.string' => __('lunar-api::validation.cart_addresses.delivery_instructions.string'),
            'contact_email.string' => __('lunar-api::validation.cart_addresses.contact_email.string'),
            'contact_phone.string' => __('lunar-api::validation.cart_addresses.contact_phone.string'),
            'shipping_option.string' => __('lunar-api::validation.cart_addresses.shipping_option.string'),
            'address_type.required' => __('lunar-api::validation.cart_addresses.address_type.required'),
            'address_type.string' => __('lunar-api::validation.cart_addresses.address_type.string'),
            'address_type.in' => __('lunar-api::validation.cart_addresses.address_type.in'),
        ];
    }
}
