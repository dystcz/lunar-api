<?php

namespace Dystcz\LunarApi\Domain\Users\Contracts;

use Illuminate\Foundation\Auth\User as Authenticatable;

interface RegistersUser
{
    /**
     * Create a newly registered user.
     */
    public function register(UserData $data): Authenticatable;
}
