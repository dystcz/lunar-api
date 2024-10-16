<?php

namespace Dystcz\LunarApi\Base\Concerns;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

trait HasAuth
{
    protected string $authGuard = 'web';

    public function auth(): Guard
    {
        return Auth::guard($this->getAuthGuard());
    }

    public function authGuard(string $guard): static
    {
        $this->authGuard = $guard;

        return $this;
    }

    public function getAuthGuard(): string
    {
        return $this->authGuard;
    }
}
