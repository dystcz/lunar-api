<?php

namespace Dystcz\LunarApi\Domain\Auth\JsonApi\V1;

use Illuminate\Validation\Rules\Password;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class NewPasswordRequest extends ResourceRequest
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
                'email',
            ],
            'token' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
                Password::min(8),
                'confirmed',
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
        return [
            'email.required' => __('lunar-api::validations.users.email.required'),
            'email.email' => __('lunar-api::validations.users.email.email'),
            'token.required' => __('lunar-api::validations.users.token.required'),
            'token.string' => __('lunar-api::validations.users.token.string'),
            'password.min' => __('lunar-api::validations.users.password.min'),
            'password.required' => __('lunar-api::validations.users.password.required'),
            'password.string' => __('lunar-api::validations.users.password.string'),
            'password.confirmed' => __('lunar-api::validations.users.password.confirmed'),
        ];
    }
}
