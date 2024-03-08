<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class ClearUserCartController extends Controller
{
    /**
     * @param  CartSessionManager  $cartSession
     */
    private CartSessionInterface $cartSession;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Clear all items from user's cart.
     */
    public function clear(): JsonResponse
    {
        $this->authorize('clear', $this->cartSession->current());

        $this->cartSession->clear();

        return new JsonResponse(status: 204);
    }
}
