<?php

namespace Dystcz\LunarApi\Domain\Auth\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class ForgottenPasswordRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation messages for the request.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        // TODO: Translate
        return [
            'email.required' => __('lunar-api::validations.auth.email.required'),
            'email.string' => __('lunar-api::validations.auth.email.string'),
            'email.email' => __('lunar-api::validations.auth.email.email'),
            'email.max' => __('lunar-api::validations.auth.email.max'),
        ];
    }
}
