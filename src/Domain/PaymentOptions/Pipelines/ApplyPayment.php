<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Pipelines;

use Closure;
use Lunar\Models\Cart;

class ApplyPayment
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(): void  $next
     */
    public function handle(Cart $cart, Closure $next): void
    {
        //
    }
}
