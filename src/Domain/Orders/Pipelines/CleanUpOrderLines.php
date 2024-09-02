<?php

namespace Dystcz\LunarApi\Domain\Orders\Pipelines;

use Closure;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Lunar\Models\Contracts\Order as OrderContract;

class CleanUpOrderLines
{
    /**
     * @param  Closure(OrderContract): mixed  $next
     * @return Closure(OrderContract): mixed
     */
    public function handle(OrderContract $order, Closure $next): mixed
    {
        /** @var Order $order */
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
