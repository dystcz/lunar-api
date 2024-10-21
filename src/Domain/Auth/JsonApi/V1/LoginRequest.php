<?php

namespace Dystcz\LunarApi\Domain\Auth\JsonApi\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
            ],
            'password' => [
                'required',
                'string',
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
            'password.required' => __('lunar-api::validations.auth.password.required'),
            'password.string' => __('lunar-api::validations.auth.password.string'),
        ];
    }
}
