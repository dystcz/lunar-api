<?php

namespace Dystcz\LunarApi\Domain\Carts\Policies;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

class CartPolicy
{
    use HandlesAuthorization;

    private Request $request;

    /**
     * @var CartSessionManager
     */
    private CartSessionInterface $cartSession;

    public function __construct()
    {
        $this->request = App::get('request');

        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Authorize a user to view cart's cart lines.
     */
    public function viewCartLines(?Authenticatable $user, Cart $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?Authenticatable $user, Cart $cart): bool
    {
        return $this->check($user, $cart);
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
     * Determine whether the user can create empty addresses.
     */
    public function createEmptyAddresses(?Authenticatable $user, Cart $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function checkout(?Authenticatable $user, Cart $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update payment option.
     */
    public function updatePaymentOption(?Authenticatable $user, Cart $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update coupon.
     */
    public function updateCoupon(?Authenticatable $user, Cart $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function clear(?Authenticatable $user, Cart $cart): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, Cart $cart): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    protected function check(?Authenticatable $user, Cart $cart): bool
    {
        return (string) $this->cartSession->current()?->getRouteKey() === (string) $cart->getRouteKey();
    }
}
