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
        if ($user && $user->id === $order->user_id) {
            return true;
        }

        if (CartSession::current()->id === $order->cart_id) {
            return true;
        }

        if (
            Config::get('lunar-api.domains.orders.sign_show_route', false)
            && $this->request->hasValidSignature()
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
}
