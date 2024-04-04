<?php

namespace Dystcz\LunarApi\Domain\Auth\Listeners;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Exception;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Listeners\CartSessionAuthListener as LunarCartSessionAuthListener;
use Lunar\Managers\CartSessionManager;

class CartSessionAuthListener extends LunarCartSessionAuthListener
{
    protected CartSessionManager $cartSession;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
    ) {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Handle the event.
     */
    public function handle(Login|Logout $event): void
    {
        match (true) {
            $event instanceof Login => $this->login($event),
            $event instanceof Logout => $this->logout($event),
            default => throw new Exception('Unknown event type'),
        };
    }

    /**
     * Handle the login event.
     */
    public function login(Login $event): void
    {
        parent::login($event);

        if (! is_lunar_user($event->user)) {
            return;
        }

        // Does this user have a cart?
        $userCart = Cart::query()
            ->where('user_id', $event->user->getKey())
            ->active()
            ->first();

        if ($userCart) {
            $this->cartSession->use($userCart);
        }
    }
}
