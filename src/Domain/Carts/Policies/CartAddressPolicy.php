<?php

namespace Dystcz\LunarApi\Domain\Carts\Policies;

use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Lunar\Facades\CartSession;

class CartAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?Authenticatable $user, CartAddress $cartAddress): bool
    {
        return $cartAddress->cart_id === CartSession::manager()->id;
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(?Authenticatable $user): bool
    {
        $cartId = (int) Arr::get(request()->all(), 'data.relationships.cart.data.id', 0);
        
        if (!$cartId) {
            return false;
        }

        return CartSession::manager()->id === $cartId;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?Authenticatable $user, CartAddress $cartAddress): bool
    {
        return $cartAddress->cart_id === CartSession::manager()->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, CartAddress $cartAddress): bool
    {
        return $this->update($user, $cartAddress);
    }
}
