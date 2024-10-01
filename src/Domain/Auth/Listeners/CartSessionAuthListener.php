<?php

namespace Dystcz\LunarApi\Domain\Auth\Listeners;

use Exception;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Lunar\Listeners\CartSessionAuthListener as LunarCartSessionAuthListener;

class CartSessionAuthListener extends LunarCartSessionAuthListener
{
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
}
