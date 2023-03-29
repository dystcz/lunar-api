<?php

namespace Dystcz\LunarApi\Domain\Carts\Events;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Foundation\Events\Dispatchable;

class CartCreated
{
    use Dispatchable;

    public function __construct(public Cart $cart)
    {
        //
    }
}
