<?php

namespace Dystcz\LunarApi\Domain\Addresses\JsonApi\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule;

class AddressRequest extends ResourceRequest
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
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'company_name' => ['nullable', 'string'],
            'line_one' => ['nullable', 'string'],
            'line_two' => ['nullable', 'string'],
            'line_three' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'postcode' => ['nullable', 'string'],
            'delivery_instructions' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'string'],
            'contact_phone' => ['nullable', 'string'],
            'shipping_default' => ['nullable', 'boolean'],
            'billing_default' => ['nullable', 'boolean'],

            'customer' => [Rule::toOne(), 'required'],
            'country' => [Rule::toOne(), 'required']
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
        $validator->after(function ($validator) {
            $newCustomerId = (int) $this->input('data.relationships.customer.data.id');

            if (! $newCustomerId) {
                return;
            }

            if (! Auth::user()->customers->pluck('id')->contains($newCustomerId)) {
                $validator->errors()->add(
                    'customer',
                    'The customer does not belong to the authenticated user.'
                );
            }
        });
    }
}