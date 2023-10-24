<?php

namespace Dystcz\LunarApi\Domain\CartLines\Actions;

use Dystcz\LunarApi\Domain\CartLines\Data\CartLineData;
use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
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
