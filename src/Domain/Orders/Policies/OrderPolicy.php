<?php

namespace Dystcz\LunarApi\Domain\Orders\Policies;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class OrderPolicy
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
     * Determine whether the user can view the model.
     */
    public function view(?Authenticatable $user, Order $order): bool
    {
        return $this->orderAccessible($user, $order);
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
        return $this->orderAccessible($user, $order);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, Order $order): bool
    {
        return $this->orderAccessible($user, $order);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewSigned(?Authenticatable $user, Order $order): bool
    {
        // If order check payment status signature is valid or env is local
        if ($this->checkValidSignature() || App::environment('local')) {
            return true;
        }

        return false;
    }

    /**
     * Check if request has valid signature.
     */
    protected function checkValidSignature(): bool
    {
        return $this->request->hasValidSignatureWhileIgnoring([
            'include',
            'fields',
            'sort',
            'page',
            'filter',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    protected function orderAccessible(?Authenticatable $user, Order $order): bool
    {
        $user = $user ?? Auth::guard('sanctum')->user();

        // If order belongs to user
        if ($user && $user->getKey() === $order->user_id) {
            return true;
        }

        if (
            // If cart should not be forgotten after order is created, check if cart id matches
            ! Config::get('lunar-api.domains.carts.settings.forget_cart_after_order_created', true)
                && $this->cartSession->current()->getKey() === $order->cart_id) {
            return true;
        }

        $signsUrl = Config::get('lunar-api.domains.orders.settings.sign_show_route', true);

        // If order show route should be signed and signature is valid
        if (($signsUrl && $this->checkValidSignature()) || App::environment('local')) {
            return true;
        }

        return false;
    }
}
