<?php

namespace Dystcz\LunarApi\Domain\Users\Data;

use Dystcz\LunarApi\Domain\Users\Contracts\UserData as UserDataContract;

class UserData implements UserDataContract
{
    public function __construct(
        public string $email,
        public ?string $name = null,
        public ?string $password = null,
    ) {}

    /**
     * Get the email.
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * Get the name.
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * Get the password.
     */
    public function password(): ?string
    {
        return $this->password;
    }

    /**
     * Cast the data to an array.
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
        ];
    }
}
