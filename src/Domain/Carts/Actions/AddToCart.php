<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\CartLines\Data\CartLineData;
use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\ArrayShape;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;
use Lunar\Models\Contracts\Cart as CartContract;
use Lunar\Models\Contracts\CartLine as CartLineContract;

class AddToCart extends Action
{
    protected CartSessionManager $cartSession;

    public function __construct(
    ) {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    #[ArrayShape([CartContract::class, CartLineContract::class])]
    public function handle(CartLineData $data): array
    {
        /** @var Cart $cart */
        $cart = $this->getCart();

        $purchasable = $data->purchasable_type::find($data->purchasable_id);

        $cart = $cart->add(
            purchasable: $purchasable,
            quantity: $data->quantity,
            meta: $data->meta ?? []
        );

        $cartLine = $cart->lines->firstWhere(
            /** @var CartLine $cartLine */
            fn (CartLineContract $cartLine) => $cartLine->purchasable->is($purchasable)
        );

        $this->cartSession->use($cart);

        return [$cart, $cartLine];
    }

    /**
     * Get cart from session or create a new one.
     */
    private function getCart(): CartContract
    {
        /** @var Cart $cart */
        return $this->cartSession->current() ?? CreateCart::run();
    }
}
