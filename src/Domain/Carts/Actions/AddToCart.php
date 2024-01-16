<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\CartLines\Data\CartLineData;
use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\ArrayShape;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class AddToCart
{
    protected CartSessionManager $cartSession;

    public function __construct(
    ) {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    #[ArrayShape([Cart::class, CartLine::class])]
    public function __invoke(CartLineData $data): array
    {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $purchasable = $data->purchasable_type::find($data->purchasable_id);

        $cart = $cart->add(
            purchasable: $purchasable,
            quantity: $data->quantity,
            meta: $data->meta ?? []
        );

        $cartLine = $cart->lines->firstWhere(
            fn (CartLine $cartLine) => $cartLine->purchasable->is($purchasable)
        );

        return [$cart, $cartLine];
    }
}
