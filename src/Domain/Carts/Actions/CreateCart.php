<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;
use Lunar\Models\Cart;
use Lunar\Models\Contracts\Cart as CartContract;

class CreateCart extends Action
{
    protected CartSessionManager $cartSession;

    protected AuthManager $authManager;

    public function __construct(
    ) {
        $this->authManager = App::make(AuthManager::class);

        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Create a new cart.
     */
    public function handle(): CartContract
    {
        $cart = Cart::modelClass()::create([
            'currency_id' => $this->cartSession->getCurrency()->id,
            'channel_id' => $this->cartSession->getChannel()->id,
            'user_id' => $this->authManager->user()?->id,
        ]);

        return $this->cartSession->use($cart);
    }
}
