<?php

namespace Dystcz\LunarApi\Domain\CartLines\Actions;

use Dystcz\LunarApi\Domain\CartLines\Data\CartLineData;
use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class UpdateCartLine extends Action
{
    protected CartSessionManager $cartSession;

    public function __construct(
    ) {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    public function handle(CartLineData $data, CartLine $cartLine): array
    {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $cart = $cart->updateLine(
            cartLineId: $cartLine->id,
            quantity: $data->quantity,
            meta: $data->meta ?? []
        );

        $cartLine = $cart->lines->firstWhere(
            fn (CartLine $cartLine) => $cartLine->is($cartLine)
        );

        return [$cart, $cartLine];
    }
}
