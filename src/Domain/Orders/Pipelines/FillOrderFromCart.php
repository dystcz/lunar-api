<?php

namespace Dystcz\LunarApi\Domain\Orders\Pipelines;

use Closure;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Lunar\Actions\Orders\GenerateOrderReference;
use Lunar\Facades\ModelManifest;
use Lunar\Models\Contracts\Currency as CurrencyContract;
use Lunar\Models\Contracts\Order as OrderContract;

class FillOrderFromCart
{
    /**
     * @param  Closure(OrderContract): mixed  $next
     * @return Closure
     */
    public function handle(OrderContract $order, Closure $next): mixed
    {
        /** @var Order $order */
        $cart = $order->cart->calculate();

        $order->fill([
            'user_id' => $cart->user_id,
            'customer_id' => $cart->customer_id,
            'channel_id' => $cart->channel_id,
            'status' => config('lunar.orders.draft_status'),
            'reference' => null,
            'customer_reference' => null,
            'sub_total' => $cart->subTotal->value,
            'total' => $cart->total->value,
            'discount_total' => $cart->discountTotal?->value,
            'discount_breakdown' => [],
            'shipping_total' => $cart->shippingTotal?->value ?: 0,
            'shipping_breakdown' => $cart->shippingBreakdown,
            'payment_total' => $cart->paymentTotal?->value ?: 0,
            'payment_breakdown' => $cart->paymentBreakdown,
            'tax_breakdown' => $cart->taxBreakdown,
            'tax_total' => $cart->taxTotal->value,
            'currency_code' => $cart->currency->code,
            'exchange_rate' => $cart->currency->exchange_rate,
            'compare_currency_code' => ModelManifest::get(CurrencyContract::class)::getDefault()?->code,
            'meta' => $cart->meta,
        ])->save();

        $order->update([
            'reference' => app(GenerateOrderReference::class)->execute($order),
        ]);

        return $next($order);
    }
}
