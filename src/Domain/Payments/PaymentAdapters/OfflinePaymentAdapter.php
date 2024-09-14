<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent as PaymentIntentContract;
use Dystcz\LunarApi\Domain\Payments\Data\OfflinePaymentIntent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Contracts\Cart as CartContract;

class OfflinePaymentAdapter extends PaymentAdapter
{
    public function __construct() {}

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
        return 'offline';
    }

    /**
     * Create payment intent.
     */
    public function createIntent(CartContract $cart, array $meta = [], ?int $amount = null): PaymentIntentContract
    {
        /** @var Cart $cart */
        $cart = $this->updateCartMeta($cart, $meta);
        $order = $this->getOrCreateOrder($cart);

        $paymentIntent = new OfflinePaymentIntent(
            amount: $order->total->value,
            id: "{$this->getType()}-{$order->reference}",
            meta: array_merge($meta, ['payment_method' => $this->getType()]),
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
    protected function updateCartMeta(CartContract $cart, array $meta = []): CartContract
    {
        if (empty($meta)) {
            return $cart;
        }

        /** @var Cart $cart */
        $cart->update('meta', [
            ...$cart->meta,
            ...$meta,
        ]);

        return $cart;
    }
}
