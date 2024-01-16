<?php

namespace Dystcz\LunarApi\Domain\Users\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface UserData extends Arrayable
{
    /**
     * Get the email.
     */
    public function email(): string;

    /**
     * Get the name.
     */
    public function name(): ?string;

    /**
     * Get the password.
     */
    public function password(): ?string;
}
