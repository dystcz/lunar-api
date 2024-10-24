<?php

namespace Dystcz\LunarApi\Domain\Carts\Policies;

use Dystcz\LunarApi\Domain\Auth\Concerns\HandlesAuthorization;
use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lunar\Models\Contracts\Cart as CartContract;

class CartPolicy
{
    use HandlesAuthorization;

    private Request $request;

    public function __construct()
    {
        $this->request = App::get('request');
    }

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
    public function view(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's addresses.
     */
    public function viewAddresses(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's shipping addresses.
     */
    public function viewShippingAddress(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's billing addresses.
     */
    public function viewBillingAddress(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's cart lines.
     */
    public function viewLines(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's orders.
     */
    public function viewOrders(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's draft order.
     */
    public function viewDraftOrder(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's completed orders.
     */
    public function viewCompletedOrders(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's completed order.
     */
    public function viewCompletedOrder(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's cart lines.
     */
    public function viewCartLines(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's cart addresses.
     */
    public function viewCartAddresses(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's purchasable.
     */
    public function viewPurchasable(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's currency.
     */
    public function viewCurrency(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's user.
     */
    public function viewUser(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $cart);
    }

    /**
     * Authorize a user to view cart's customer.
     */
    public function viewCustomer(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

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
    public function update(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create empty addresses.
     */
    public function createEmptyAddresses(?Authenticatable $user, CartContract $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function checkout(?Authenticatable $user, CartContract $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update payment option.
     */
    public function updatePaymentOption(?Authenticatable $user, CartContract $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update coupon.
     */
    public function updateCoupon(?Authenticatable $user, CartContract $cart): bool
    {
        return $this->check($user, $cart);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function clear(?Authenticatable $user, CartContract $cart): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, CartContract $cart): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    protected function check(?Authenticatable $user, CartContract $cart): bool
    {
        return (string) App::make(CurrentSessionCart::class)?->getRouteKey() === (string) $cart->getRouteKey();
    }
}
