<?php

namespace Dystcz\LunarApi\Domain\Orders\Policies;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Facades\CartSession;

class OrderPolicy
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
    public function view(?Authenticatable $user, Order $order): bool
    {
        return $this->update($user, $order);
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
    public function update(?Authenticatable $user, Order $order): bool
    {
        if ($user && $user->getKey() === $order->user_id) {
            return true;
        }

        if (
            // If cart should not be forgotten after order is created, check if cart id matches
            ! Config::get('lunar-api.domains.cart.forget_cart_after_order_created', true)
                && CartSession::current()->id === $order->cart_id) {
            return true;
        }

        if (
            // If order show route should be signed and signature is valid
            (Config::get('lunar-api.domains.orders.sign_show_route', true) && $this->request->hasValidSignature())
                // If env is  local
                || App::environment('local')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, Order $order): bool
    {
        return $this->update($user, $order);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewSigned(?Authenticatable $user, Order $order): bool
    {
        // If order check payment status signature is valid or env is local
        if ($this->request->hasValidSignature() || App::environment('local')) {
            return true;
        }

        return false;
    }
}
