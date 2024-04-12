<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class CheckoutCartRequest extends ResourceRequest
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
            'create_user.boolean' => __('lunar-api::validations.carts.create_user.boolean'),
            'meta.array' => __('lunar-api::validations.carts.meta.array'),
            'agree.accepted' => __('lunar-api::validations.carts.agree.accepted'),
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

            $this->validateShippingOption($validator, $cart);
            $this->validateStock($validator, $cart);
        });
    }

    /**
     * Validate the shipping option.
     */
    protected function validateShippingOption(Validator $validator, Cart $cart): void
    {
        /** @var CartAddress $shippingAddress */
        $shippingAddress = $cart->shippingAddress;

        if (!$shippingAddress->hasShippingOption()) {
            $validator->errors()->add(
                'cart',
                __('lunar-api::validations.carts.shipping_option.required'),
            );
        }
    }

    /**
     * Validate the stock.
     */
    protected function validateStock(Validator $validator, Cart $cart): void
    {
        if (!config('lunar-api.general.checkout.check_stock_on_checkout')) {
            return;
        }

        $cart->lines->each(function ($line) use ($validator) {
            if ($line->purchasable->inStockQuantity < $line->quantity) {
                $validator->errors()->add(
                    'cart',
                    __('lunar-api::validations.carts.products.out_of_stock'),
                );
            }
        });
    }
}
