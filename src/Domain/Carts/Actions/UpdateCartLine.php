<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Data\CartLineData;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Carts\Models\CartLine;
use JetBrains\PhpStorm\ArrayShape;
use Lunar\Facades\CartSession;

class UpdateCartLine
{
    #[ArrayShape([Cart::class, CartLine::class])]
    public function __invoke(CartLineData $data, CartLine $cartLine): array
    {
        /** @var Cart $cart */
        $cart = CartSession::current();

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
