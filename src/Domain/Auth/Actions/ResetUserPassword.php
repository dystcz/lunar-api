<?php

namespace Dystcz\LunarApi\Domain\Auth\Actions;

use Dystcz\LunarApi\Domain\Users\Contracts\User as UserContract;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Action
{
    /**
     * Reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function handle(UserContract $user, array $input): void
    {
        /** @var User $user */
        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
