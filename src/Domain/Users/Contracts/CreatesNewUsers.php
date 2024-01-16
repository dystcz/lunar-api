<?php

namespace Dystcz\LunarApi\Domain\Users\Contracts;

use Illuminate\Foundation\Auth\User as Authenticatable;

interface CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     */
    public function create(UserData $data): Authenticatable;
}
