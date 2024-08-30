<?php

namespace Dystcz\LunarApi\Domain\Orders\Pipelines;

use Closure;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Lunar\Facades\ModelManifest;
use Lunar\Models\Contracts\Order as OrderContract;
use Lunar\Models\Contracts\OrderLine as OrderLineContract;

class CreatePaymentLine
{
    /**
     * Create payment line for order.
     *
     * @param  Closure(OrderContract): mixed  $next
     * @return mixed
     */
    public function handle(OrderContract $order, Closure $next)
    {
        /** @var Order $order */
        /** @var Cart $cart */
        $cart = $order->cart->recalculate();

        $orderLineClass = ModelManifest::get(OrderLineContract::class);

        if ($paymentOption = $cart->getPaymentOption()) {
            $paymentLine = $order->lines->first(function ($orderLine) use ($paymentOption) {
                return $orderLine->type == 'payment' &&
                    $orderLine->purchasable_type == PaymentOption::class &&
                    $orderLine->identifier == $paymentOption->getIdentifier();
            }) ?: new $orderLineClass;

            $paymentLine->fill([
                'order_id' => $order->id,
                'purchasable_type' => PaymentOption::class,
                'purchasable_id' => 1,
                'type' => 'payment',
                'description' => $paymentOption->getDescription(),
                'option' => $paymentOption->getOption(),
                'identifier' => $paymentOption->getIdentifier(),
                'unit_price' => $paymentOption->price->value,
                'unit_quantity' => $paymentOption->getUnitQuantity(),
                'quantity' => 1,
                'sub_total' => $cart->paymentSubTotal->value,
                'discount_total' => $cart->paymentSubTotal->discountTotal?->value ?: 0,
                'tax_breakdown' => $cart->paymentTaxBreakdown,
                'tax_total' => $cart->paymentTaxTotal->value,
                'total' => $cart->paymentTotal->value,
                'notes' => null,
                'meta' => [],
            ])->save();
        }

        return $next($order->refresh());
    }
}
