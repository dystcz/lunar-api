<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Illuminate\Http\Response;
use Lunar\Facades\CartSession;

class ClearUserCartController extends Controller
{
    /**
     * Clear all items from user's cart.
     */
    public function clear(): Response
    {
        // $this->authorize('delete', Cart::class);

        CartSession::clear();

        return response('', 204);
    }
}
