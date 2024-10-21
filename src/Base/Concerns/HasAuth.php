<?php

namespace Dystcz\LunarApi\Base\Concerns;

use Dystcz\LunarApi\Domain\Carts\Contracts\CheckoutCart;
use Dystcz\LunarApi\Domain\Users\Contracts\CreatesNewUsers;
use Dystcz\LunarApi\Domain\Users\Contracts\CreatesUserFromCart;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\App;
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

    /**
     * Create new user using class.
     */
    public function createUserUsing(string $class): static
    {
        App::singleton(CreatesNewUsers::class, $class);

        return $this;
    }

    /**
     * Create user from cart using class.
     */
    public function createUserFromCartUsing(string $class): static
    {
        App::singleton(CreatesUserFromCart::class, $class);

        return $this;
    }

    /**
     * Register user using class.
     */
    public function registerUserUsing(string $class): static
    {
        App::singleton(RegistersUser::class, $class);

        return $this;
    }

    /**
     * Checkout cart using class.
     */
    public function checkoutCartUsing(string $class): static
    {
        App::singleton(CheckoutCart::class, $class);

        return $this;
    }
}
