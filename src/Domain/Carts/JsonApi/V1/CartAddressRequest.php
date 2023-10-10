<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressRequest;
use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Validation\Rule as JsonApiRule;
use Lunar\Facades\CartSession;

class CartAddressRequest extends AddressRequest
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
                    'shipping',
                    'billing',
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
        ];
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
