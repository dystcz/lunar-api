<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Base\Concerns;
use Dystcz\LunarApi\Domain\Carts\Contracts\CheckoutCart;
use Dystcz\LunarApi\Domain\Users\Contracts\CreatesNewUsers;
use Dystcz\LunarApi\Domain\Users\Contracts\CreatesUserFromCart;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LunarApi
{
    use Concerns\HasAuth;

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

    /**
     * Check if hashids are used.
     */
    public function usesHashids(): bool
    {
        return Config::get('lunar-api.general.use_hashids', false);
    }
}
