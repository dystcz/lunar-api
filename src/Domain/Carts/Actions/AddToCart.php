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
        /** @var Cart $cart */
        $cart = CartSession::manager();

        $purchasable = $data->purchasable_type::find($data->purchasable_id);

        $cart = $cart->add(
            purchasable: $purchasable,
            quantity: $data->quantity,
            meta: $data->meta ?? []
        );

        $cartLine = $cart->lines()
            ->where('purchasable_id', $data->purchasable_id)
            ->where('purchasable_type', $data->purchasable_type)
            ->first();

        return [$cart, $cartLine];
    }
}
