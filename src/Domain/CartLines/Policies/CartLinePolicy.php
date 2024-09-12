<?php

namespace Dystcz\LunarApi\Domain\CartLines\Policies;

use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Models\Contracts\Cart;
use Lunar\Models\Contracts\CartLine;

class CartLinePolicy
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
    public function view(?Authenticatable $user, CartLine $cartLine): bool
    {
        return true;
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
    public function update(?Authenticatable $user, CartLine $cartLine): bool
    {
        return $this->check($user, $cartLine);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, CartLine $cartLine): bool
    {
        return $this->check($user, $cartLine);
    }

    /**
     * Determine whether the user has access to the model.
     */
    public function check(?Authenticatable $user, CartLine $cartLine): bool
    {
        /** @var Cart $cart */
        $cart = App::make(CurrentSessionCart::class);

        return $cart->lines->contains($cartLine->id);
    }
}
