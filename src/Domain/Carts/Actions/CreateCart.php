<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;
use Lunar\Models\Cart;

class CreateCart extends Action
{
    protected CartSessionManager $cartSession;

    public function __construct(
        protected AuthManager $authManager,
    ) {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Create a new cart.
     */
    public function handle(): Cart
    {
        $cart = Cart::create([
            'currency_id' => $this->cartSession->getCurrency()->id,
            'channel_id' => $this->cartSession->getChannel()->id,
            'user_id' => $this->authManager->user()?->id,
        ]);

        return $this->cartSession->use($cart);
    }
}
