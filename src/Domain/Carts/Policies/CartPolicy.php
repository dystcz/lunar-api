<?php

namespace Dystcz\LunarApi\Domain\Carts\Policies;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?Authenticatable $user, Cart $cart): bool
    {
        /**
         * Authorize user to view cart only if the user's IP address matches the cart's auth_user_ip.
         */
        $cartAuthUserIp = $cart->meta->auth_user_ip ?? null;
        $userIp = request()->ip();

        if (! $userIp || ! $cartAuthUserIp) {
            return false;
        }

        if ($userIp === $cartAuthUserIp) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(?Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?Authenticatable $user, Cart $cart): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, Cart $cart): bool
    {
        return false;
    }
}
