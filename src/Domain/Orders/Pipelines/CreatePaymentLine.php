<?php

namespace Dystcz\LunarApi\Domain\Orders\Pipelines;

use Closure;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Lunar\Base\ValueObjects\Cart\TaxBreakdown;
use Lunar\Models\Order;
use Lunar\Models\OrderLine;

class CreatePaymentLine
{
    /**
     * Create payment line for order.
     *
     * @param  Closure(Order): void  $next
     * @return void
     */
    public function handle(Order $order, Closure $next)
    {
        /** @var Cart $cart */
        $cart = $order->cart->calculate();

        if ($paymentOption = $cart->getPaymentOption()) {
            $paymentLine = $order->lines->first(function ($orderLine) use ($paymentOption) {
                return $orderLine->type == 'payment' &&
                    $orderLine->purchasable_type == PaymentOption::class &&
                    $orderLine->identifier == $paymentOption->getIdentifier();
            }) ?: new OrderLine;

            $paymentTaxBreakdown = new TaxBreakdown(
                $cart->taxBreakdown->amounts->where('identifier', $paymentOption->getIdentifier()),
            );

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
                'tax_breakdown' => $paymentTaxBreakdown,
                'tax_total' => $cart->paymentTaxTotal->value,
                'total' => $cart->paymentTotal->value,
                'notes' => null,
                'meta' => [],
            ])->save();

            $order->update([
                'payment_total' => $cart->paymentTotal->value,
                'payment_breakdown' => $cart->paymentBreakdown,
            ]);
        }

        return $next($order->refresh());
    }
}
