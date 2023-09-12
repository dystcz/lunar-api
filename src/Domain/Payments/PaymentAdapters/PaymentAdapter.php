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

    abstract public function getDriver(): string;

    abstract public function getType(): string;

    abstract public function createIntent(Cart $cart): PaymentIntent;

    abstract public function handleWebhook(Request $request): JsonResponse;

    protected function createTransaction(string|int $intentId, float $amount, array $data = []): void
    {
        Transaction::create([
            'type' => 'intent',
            'order_id' => $this->cart->order->id,
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
