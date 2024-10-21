<?php

namespace Dystcz\LunarApi\Domain\Users\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class UpdateUserRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'first_name' => [
                'required',
                'string',
                'max:255',
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'nullable',
                // 'phone',
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
            'name.string' => __('lunar-api::validation.users.name.string'),
            'name.max' => __('lunar-api::validation.users.name.max'),
            'first_name.required' => __('lunar-api::validation.users.first_name.required'),
            'first_name.string' => __('lunar-api::validation.users.first_name.string'),
            'first_name.max' => __('lunar-api::validation.users.first_name.max'),
            'last_name.required' => __('lunar-api::validation.users.last_name.required'),
            'last_name.string' => __('lunar-api::validation.users.last_name.string'),
            'last_name.max' => __('lunar-api::validation.users.last_name.max'),

            'email.required' => __('lunar-api::validations.auth.email.required'),
            'email.string' => __('lunar-api::validations.auth.email.string'),
            'email.email' => __('lunar-api::validations.auth.email.email'),
            'email.max' => __('lunar-api::validations.auth.email.max'),
            'email.unique' => __('lunar-api::validations.auth.email.unique'),
            'password.required' => __('lunar-api::validations.auth.password.required'),
            'password.min' => __('lunar-api::validations.auth.password.min'),
            'password.string' => __('lunar-api::validations.auth.password.string'),
            'password.confirmed' => __('lunar-api::validations.auth.password.confirmed'),

            'phone.required' => __('lunar-api::validations.auth.phone.required'),
            'phone.phone' => __('lunar-api::validations.auth.phone.phone'),
        ];
    }
}
