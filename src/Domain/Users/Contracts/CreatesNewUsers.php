<?php

namespace Dystcz\LunarApi\Domain\Users\Contracts;

use Illuminate\Foundation\Auth\User as Authenticatable;

interface CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): Authenticatable;
}
