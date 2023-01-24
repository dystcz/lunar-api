<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Data\CartLineData;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Carts\Models\CartLine;
use JetBrains\PhpStorm\ArrayShape;
use Lunar\Facades\CartSession;

class AddToCart
{
    #[ArrayShape([Cart::class, CartLine::class])]
    public function __invoke(CartLineData $data): array
    {
        // Get the cart from the session or create a new one.
        $cart = CartSession::manager()->getCart();

        // Get the cart line from the cart or create a new one.
        $cartLine = $cart->lines()
            ->firstOrCreate([
                'purchasable_type' => $data->purchasable_type,
                'purchasable_id' => $data->purchasable_id,
            ], $data->toArray());

        // If the cart line was not recently created, increment the quantity.
        if (! $cartLine->wasRecentlyCreated) {
            $cartLine->increment('quantity', $data->quantity);
        }

        return [$cart, $cartLine];
    }
}
