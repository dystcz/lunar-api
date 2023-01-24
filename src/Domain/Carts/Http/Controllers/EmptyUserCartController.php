<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Lunar\Facades\CartSession;

class EmptyUserCartController extends Controller
{
    /**
     * Clear all items from user's cart.
     *
     * @return Response
     */
    public function empty(): Response
    {
        // $this->authorize('delete', Cart::class);

        CartSession::clear();

        return response('', 204);
    }
}
