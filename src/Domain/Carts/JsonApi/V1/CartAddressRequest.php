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
            'title' => ['nullable', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['nullable', 'string'],
            'company_name' => ['nullable', 'string'],
            'line_one' => ['required', 'string'],
            'line_two' => ['nullable', 'string'],
            'line_three' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'state' => ['nullable', 'string'],
            'postcode' => ['required', 'string'],
            'delivery_instructions' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'string'],
            'contact_phone' => ['nullable', 'string'],
            'shipping_option' => ['nullable', 'string'],
            'address_type' => ['required', 'string', Rule::in(['shipping', 'billing'])],

            'cart' => [\LaravelJsonApi\Validation\Rule::toOne(), 'required'],
            'country' => [\LaravelJsonApi\Validation\Rule::toOne()]
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
