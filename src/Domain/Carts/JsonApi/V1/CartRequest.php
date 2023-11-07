<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class CartRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        return [
            'create_user' => [
                'boolean',
            ],
            'meta' => [
                'nullable',
                'array',
            ],
            'agree' => [
                'accepted',
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
            'create_user.boolean' => __('lunar-api::validation.cart.create_user.boolean'),
            'meta.array' => __('lunar-api::validation.cart.meta.array'),
            'agree.accepted' => __('lunar-api::validation.cart.agree.accepted'),
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var CartSessionManager $cartSession */
            $cartSession = App::make(CartSessionInterface::class);

            /** @var Cart $cart */
            $cart = $cartSession
                ->current()
                ->load(['shippingAddress']);

            /** @var CartAddress $shippingAddress */
            $shippingAddress = $cart->shippingAddress;

            if (! $shippingAddress->hasShippingOption()) {
                $validator->errors()->add(
                    'cart',
                    __('lunar-api::validation.cart.shipping_option.required'),
                );
            }
        });
    }
}
