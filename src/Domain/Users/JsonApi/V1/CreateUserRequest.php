<?php

namespace Dystcz\LunarApi\Domain\Users\JsonApi\V1;

use Dystcz\LunarApi\Domain\Users\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CreateUserRequest extends ResourceRequest
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
                Rule::unique(User::class),
            ],
            'password' => [
                'required',
                'string',
                Password::min(8),
                'confirmed',
            ],
            // 'accept_terms' => [
            //     'required',
            //     'accepted',
            // ],
        ];
    }

    /**
     * Get the validation messages.
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
            'email.unique' => __('lunar-api::validations.auth.email.unique'),
            'password.required' => __('lunar-api::validations.auth.password.required'),
            'password.min' => __('lunar-api::validations.auth.password.min'),
            'password.string' => __('lunar-api::validations.auth.password.string'),
            'password.confirmed' => __('lunar-api::validations.auth.password.confirmed'),

            // 'accept_terms.required' => __('validation.auth.accept_terms.required'),
            // 'accept_terms.accepted' => __('validation.auth.accept_terms.accepted'),
        ];
    }
}
