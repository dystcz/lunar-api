<?php

namespace Dystcz\LunarApi\Domain\Carts\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Lunar\Models\Contracts\Cart;

class CartCreated
{
    use Dispatchable;

    public function __construct(public Cart $cart)
    {
        //
    }
}
