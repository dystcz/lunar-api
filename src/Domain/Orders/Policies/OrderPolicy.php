<?php

namespace Dystcz\LunarApi\Domain\Orders\Policies;

use Dystcz\LunarApi\Domain\Checkout\Enums\CheckoutProtectionStrategy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;
use Lunar\Models\Contracts\Order;

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
        return $this->check($user, $order);
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
        return $this->check($user, $order);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, Order $order): bool
    {
        return $this->check($user, $order);
    }

    /**
     * Authorize a user to view order's order lines.
     */
    public function viewOrderLines(?Authenticatable $user, Order $order): bool
    {
        return $this->check($user, $order);
    }

    /**
     * Authorize a user to view order's digital lines.
     */
    public function viewDigitalLines(?Authenticatable $user, Order $order): bool
    {
        return $this->check($user, $order);
    }

    /**
     * Authorize a user to view order's product lines.
     */
    public function viewProductLines(?Authenticatable $user, Order $order): bool
    {
        return $this->check($user, $order);
    }

    /**
     * Authorize a user to view order's physical lines.
     */
    public function viewPhysicalLines(?Authenticatable $user, Order $order): bool
    {
        return $this->check($user, $order);
    }

    /**
     * Authorize a user to view order's shipping lines.
     */
    public function viewShippingLines(?Authenticatable $user, Order $order): bool
    {
        return $this->check($user, $order);
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
    protected function check(?Authenticatable $user, Order $order): bool
    {
        $protectionStrategy = Config::get(
            'lunar-api.general.checkout.checkout_protection_strategy',
            CheckoutProtectionStrategy::SIGNATURE,
        );

        // If env is local, skip protection
        if (App::environment('local')) {
            return true;
        }

        // If no protection strategy is set
        if ($protectionStrategy === CheckoutProtectionStrategy::NONE) {
            return true;
        }

        // If order belongs to user
        if ($user && $user->getKey() === $order->user_id) {
            return true;
        }

        if (
            // If cart should not be forgotten after order is created, check if cart id matches
            ! Config::get('lunar-api.general.checkout.forget_cart_after_order_creation', true)
                && $this->cartSession->current()->getKey() === $order->cart_id) {
            return true;
        }

        // If order checkout routes should be signed and signature is valid
        if ($protectionStrategy === CheckoutProtectionStrategy::SIGNATURE && $this->checkValidSignature()) {
            return true;
        }

        return false;
    }
}
