<?php

namespace Dystcz\LunarApi\Domain\Auth\JsonApi\Proxies;

use Dystcz\LunarApi\Domain\Users\Models\User;
use LaravelJsonApi\Eloquent\Proxy;

class AuthUser extends Proxy
{
    /**
     * UserAccount constructor.
     */
    public function __construct(?User $user = null)
    {
        parent::__construct($user ?: new User);
    }
}
