<?php

namespace Dystcz\LunarApi\Domain\Users\JsonApi\V1;

use Dystcz\LunarApi\Domain\Users\Rules\CorrectOldPassword;
use Illuminate\Validation\Rules\Password;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class ChangePasswordRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                Password::min(8),
                'confirmed',
            ],
            'old_password' => [
                'required',
                'string',
                Password::min(8),
                new CorrectOldPassword($this->model()),
            ],
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
            'password.min' => __('lunar-api::validations.users.password.min'),
            'password.required' => __('lunar-api::validations.users.password.required'),
            'password.string' => __('lunar-api::validations.users.password.string'),
            'password.confirmed' => __('lunar-api::validations.users.password.confirmed'),
            'old_password.required' => __('lunar-api::validations.users.old_password.required'),
            'old_password.string' => __('lunar-api::validations.users.old_password.string'),
        ];
    }
}
