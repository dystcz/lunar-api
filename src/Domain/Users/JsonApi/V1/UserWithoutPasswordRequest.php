<?php

namespace Dystcz\LunarApi\Domain\Users\JsonApi\V1;

use Dystcz\LunarApi\Domain\Users\Models\User;
use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class UserWithoutPasswordRequest extends ResourceRequest
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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::modelClass()),
            ],
            'phone' => [
                'nullable',
                'phone',
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
            'name.string' => __('lunar-api::validations.users.name.string'),
            'name.max' => __('lunar-api::validations.users.name.max'),
            'email.required' => __('lunar-api::validations.users.email.required'),
            'email.string' => __('lunar-api::validations.users.email.string'),
            'email.email' => __('lunar-api::validations.users.email.email'),
            'email.max' => __('lunar-api::validations.users.email.max'),
            'email.unique' => __('lunar-api::validations.users.email.unique'),
            'phone.required' => __('lunar-api::validations.users.phone.required'),
            'phone.phone' => __('lunar-api::validations.users.phone.phone'),
        ];
    }
}
