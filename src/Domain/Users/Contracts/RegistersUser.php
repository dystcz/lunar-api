<?php

namespace Dystcz\LunarApi\Domain\Users\Contracts;

use Illuminate\Foundation\Auth\User as Authenticatable;

interface RegistersUser
{
    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $data
     */
    public function register(array $data): Authenticatable;
}
