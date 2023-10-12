<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lunar\Models\Cart;
use Lunar\Models\Transaction;

abstract class PaymentAdapter
{
    protected Cart $cart;

    public static function register(): void
    {
        $adapter = new static();

        App::make(PaymentAdaptersRegister::class)
            ->add($adapter->getDriver(), static::class);
    }

    /**
     * Get payment driver.
     */
    abstract public function getDriver(): string;

    /**
     * Get payment type.
     */
    abstract public function getType(): string;

    /**
     * Create payment intent.
     */
    abstract public function createIntent(Cart $cart): PaymentIntent;

    /**
     * Handle incoming webhook call.
     */
    abstract public function handleWebhook(Request $request): JsonResponse;

    /**
     * Create transaction for payment intent.
     */
    protected function createTransaction(string|int $intentId, float $amount, array $data = []): void
    {
        Transaction::create([
            'type' => 'intent',
            'order_id' => $this->cart->draftOrder->id,
            'driver' => $this->getDriver(),
            'amount' => $amount,
            'success' => true,
            'reference' => $intentId,
            'status' => 'intent',
            'card_type' => $this->getType(),
            ...$data,
        ]);
    }
}
