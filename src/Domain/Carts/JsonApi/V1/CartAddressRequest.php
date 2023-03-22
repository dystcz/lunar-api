<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use Lunar\Facades\CartSession;

class CartAddressRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'sometimes'],
            'first_name' => ['required', 'string', 'sometimes'],
            'last_name' => ['nullable', 'string', 'sometimes'],
            'company_name' => ['nullable', 'string', 'sometimes'],
            'line_one' => ['required', 'string', 'sometimes'],
            'line_two' => ['nullable', 'string', 'sometimes'],
            'line_three' => ['nullable', 'string', 'sometimes'],
            'city' => ['required', 'string', 'sometimes'],
            'state' => ['nullable', 'string', 'sometimes'],
            'postcode' => ['required', 'string', 'sometimes'],
            'delivery_instructions' => ['nullable', 'string', 'sometimes'],
            'contact_email' => ['nullable', 'string', 'sometimes'],
            'contact_phone' => ['nullable', 'string', 'sometimes'],
            'shipping_option' => ['nullable', 'string', 'sometimes'],
            'address_type' => ['required', 'string', Rule::in(['shipping', 'billing']), 'sometimes'],

            'cart' => [\LaravelJsonApi\Validation\Rule::toOne(), 'required'],
            'country' => [\LaravelJsonApi\Validation\Rule::toOne(), 'required']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
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

            if (!$newCartId) {
                return;
            }

            if (!in_array($newCartId, [$cartAddress->cart_id, CartSession::manager()->id])) {
                $validator->errors()->add(
                    'cart',
                    'Cannot change the cart of a cart address.'
                );
            }
        });
    }
}
