<?php

namespace Dystcz\LunarApi\Domain\Carts\Pipelines;

use Closure;
use Lunar\Facades\Discounts;
use Lunar\Models\Cart;
use Lunar\Models\Contracts\Cart as CartContract;

final class ApplyDiscounts
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(CartContract): mixed  $next
     * @return Closure
     */
    public function handle(CartContract $cart, Closure $next): mixed
    {
        /** @var Cart $cart */
        $cart->discounts = collect([]);
        $cart->discountBreakdown = collect([]);

        Discounts::apply($cart);

        return $next($cart);
    }
}
