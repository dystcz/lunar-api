<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1;

use Closure;
use Dystcz\LunarApi\Domain\Addresses\Http\Enums\AddressType;
use Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressRequest;
use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Validation\Rule as JsonApiRule;
use Lunar\Facades\CartSession;

class CartAddressRequest extends AddressRequest
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
                Rule::requiredIf($this->isShippingAddress()),
                'string',
            ],
            'contact_phone' => [
                Rule::requiredIf($this->isShippingAddress()),
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
                'required',
            ],
            'meta' => [
                'nullable',
                'array',
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
            'title.string' => __('lunar-api::validations.cart_addresses.title.string'),
            'first_name.required' => __('lunar-api::validations.cart_addresses.first_name.required'),
            'first_name.string' => __('lunar-api::validations.cart_addresses.first_name.string'),
            'last_name.required' => __('lunar-api::validations.cart_addresses.last_name.required'),
            'last_name.string' => __('lunar-api::validations.cart_addresses.last_name.string'),
            'company_name.string' => __('lunar-api::validations.cart_addresses.company_name.string'),
            'company_in.string' => __('lunar-api::validations.cart_addresses.company_in.string'),
            'company_tin.string' => __('lunar-api::validations.cart_addresses.company_tin.string'),
            'line_one.required' => __('lunar-api::validations.cart_addresses.line_one.required'),
            'line_one.string' => __('lunar-api::validations.cart_addresses.line_one.string'),
            'line_two.string' => __('lunar-api::validations.cart_addresses.line_two.string'),
            'line_three.string' => __('lunar-api::validations.cart_addresses.line_three.string'),
            'city.required' => __('lunar-api::validations.cart_addresses.city.required'),
            'city.string' => __('lunar-api::validations.cart_addresses.city.string'),
            'state.string' => __('lunar-api::validations.cart_addresses.state.string'),
            'postcode.required' => __('lunar-api::validations.cart_addresses.postcode.required'),
            'postcode.string' => __('lunar-api::validations.cart_addresses.postcode.string'),
            'delivery_instructions.string' => __('lunar-api::validations.cart_addresses.delivery_instructions.string'),
            'contact_email.string' => __('lunar-api::validations.cart_addresses.contact_email.string'),
            'contact_phone.string' => __('lunar-api::validations.cart_addresses.contact_phone.string'),
            'shipping_option.string' => __('lunar-api::validations.cart_addresses.shipping_option.string'),
            'address_type.required' => __('lunar-api::validations.cart_addresses.address_type.required'),
            'address_type.string' => __('lunar-api::validations.cart_addresses.address_type.string'),
            'address_type.in' => __('lunar-api::validations.cart_addresses.address_type.in', [
                'types' => implode(', ', [
                    AddressType::SHIPPING->value,
                    AddressType::BILLING->value,
                ]),
            ]),
        ];
    }

    /**
     * Determine if address type is shipping.
     *
     * @return Closure(): bool
     */
    protected function isShippingAddress(): Closure
    {
        return fn () => $this->input('data.attributes.address_type') === AddressType::SHIPPING->value;
    }

    /**
     * Determine if address type is billing.
     *
     * @return Closure(): bool
     */
    protected function isBillingAddress(): Closure
    {
        return fn () => $this->input('data.attributes.address_type') === AddressType::BILLING->value;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        if (! $this->isUpdating()) {
            return;
        }

        $validator->after(function ($validator) {
            /** @var CartAddress $cartAddress */
            $cartAddress = $this->model();

            $newCartId = (int) $this->input('data.relationships.cart.data.id');

            if (! $newCartId) {
                return;
            }

            if (! in_array($newCartId, [$cartAddress->cart_id, CartSession::current()->id])) {
                $validator->errors()->add(
                    'cart',
                    'Cannot change the cart of a cart address.'
                );
            }
        });
    }
}
