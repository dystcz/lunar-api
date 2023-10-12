<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CreatePaymentIntentRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array>
     */
    public function rules(): array
    {
        $paymentTypes = Config::get('lunar.payments.types');

        return [
            'meta' => [
                'array',
            ],
            'payment_method' => [
                'required',
                'string',
                Rule::in(array_keys($paymentTypes)),
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
