<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent as PaymentIntentContract;
use Dystcz\LunarApi\Domain\Payments\Data\CashOnDeliveryPaymentIntent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Cart;

class CashOnDeliveryPaymentAdapter extends PaymentAdapter
{
    public function __construct()
    {
    }

    /**
     * Get payment driver on which this adapter binds.
     *
     * Drivers for lunar are set in lunar.payments.types.
     * When offline is set as a driver, this adapter will be used.
     */
    public function getDriver(): string
    {
        return 'offline';
    }

    /**
     * Get payment type.
     *
     * This key serves is an identification for this adapter.
     * That means that offline driver is handled by this adapter if configured.
     */
    public function getType(): string
    {
        return 'cash-on-delivery';
    }

    /**
     * Create payment intent.
     */
    public function createIntent(Cart $cart, array $meta = [], ?int $amount = null): PaymentIntentContract
    {
        $cart = $this->updateCartMeta($cart, $meta);
        $order = $this->getOrCreateOrder($cart);

        $paymentIntent = new CashOnDeliveryPaymentIntent(
            amount: $order->total->value,
            id: "{$this->getDriver()}-{$order->reference}",
            meta: array_merge($meta, ['payment_method' => $this->getDriver()]),
        );

        $this->createIntentTransaction($cart, $paymentIntent, $meta);

        return $paymentIntent;
    }

    /**
     * Handle incoming webhook call.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        return new JsonResponse(null, 200);
    }

    /**
     * Update cart meta.
     *
     * @param  array<string,mixed>  $meta
     */
    protected function updateCartMeta(Cart $cart, array $meta = []): Cart
    {
        if (empty($meta)) {
            return $cart;
        }

        $cart->update('meta', [
            ...$this->cart->meta,
            ...$meta,
        ]);

        return $cart;
    }
}
