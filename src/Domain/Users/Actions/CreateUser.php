<?php

namespace Dystcz\LunarApi\Domain\Users\Actions;

use Dystcz\LunarApi\Domain\Users\Contracts\CreatesNewUsers;
use Dystcz\LunarApi\Domain\Users\Contracts\UserData;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateUser implements CreatesNewUsers
{
    /**
     * Create user.
     */
    public function create(UserData $data): Authenticatable
    {
        $this->validate($data);

        /** @var Authenticatable $user */
        $user = Config::get('auth.providers.users.model')::create([
            'name' => $data->name(),
            'email' => $data->email(),
            'password' => Hash::make($data->password()),
        ]);

        return $user;
    }

    /**
     * Validate the given data.
     */
    public function validate(UserData $data): void
    {
        Validator::make(
            $data->toArray(),
            $this->rules(),
            $this->messages(),
        )->validate();
    }

    /**
     * Get the validation rules.
     *
     * @return array<string, array<int, string>>
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
                Rule::unique(Config::get('auth.providers.users.model')),
            ],
            'password' => [
                'required',
                'string',
                Password::min(8),
            ],
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => __('lunar-api::validations.auth.email.required'),
            'email.email' => __('lunar-api::validations.auth.email.email'),
            'email.unique' => __('lunar-api::validations.auth.email.unique'),
            'email.max' => __('lunar-api::validations.auth.email.max'),
            'password.required' => __('lunar-api::validations.auth.password.required'),
            'password.min' => __('lunar-api::validations.auth.password.min'),
            'password.confirmed' => __('lunar-api::validations.auth.password.confirmed'),
        ];
    }
}
