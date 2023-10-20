<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Cart as LunarCart;

class Cart extends LunarCart
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CartCreated::class,
    ];
}
