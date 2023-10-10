<?php

namespace Dystcz\LunarApi\Domain\Addresses\JsonApi\V1;

use Closure;
use Dystcz\LunarApi\Domain\Addresses\Http\Enums\AddressType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class AddressRequest extends ResourceRequest
{
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
