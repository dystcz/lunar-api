<?php

namespace Dystcz\LunarApi\Domain\Users\Rules;

use Closure;
use Dystcz\LunarApi\Domain\Users\Contracts\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class CorrectOldPassword implements ValidationRule
{
    public function __construct(protected User $user) {}

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Hash::check($value, $this->user->password)) {
            $fail(__('validation.users.old_password.correct'));
        }
    }
}
