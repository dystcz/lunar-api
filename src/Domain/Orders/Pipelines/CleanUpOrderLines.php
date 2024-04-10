<?php

namespace Dystcz\LunarApi\Domain\Orders\Pipelines;

use Closure;
use Lunar\Models\Order;

class CleanUpOrderLines
{
    /**
     * @return Closure
     */
    public function handle(Order $order, Closure $next)
    {
        $cart = $order->cart;

        $purchasableTypeGroups = $cart->lines->groupBy('purchasable_type');

        foreach ($purchasableTypeGroups as $purchasableType => $purchasables) {
            $order->productLines()
                ->where('purchasable_type', $purchasableType)
                ->whereNotIn('purchasable_id', $purchasables->pluck('purchasable_id')->toArray())
                ->delete();
        }

        return $next($order);
    }
}
