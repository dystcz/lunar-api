<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

use BadMethodCallException;
use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent;
use Dystcz\LunarApi\Domain\Payments\Enums\TransactionType;
use Dystcz\LunarApi\Domain\Transactions\Actions\CreateTransaction;
use Dystcz\LunarApi\Domain\Transactions\Data\TransactionData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lunar\Models\Cart;
use Lunar\Models\Transaction;

abstract class PaymentAdapter
{
    /**
     * Register payment adapter.
     */
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
     *
     * @param  array<string,mixed>  $meta
     */
    abstract public function createIntent(Cart $cart, array $meta = []): PaymentIntent;

    /**
     * Handle incoming webhook call.
     */
    abstract public function handleWebhook(Request $request): JsonResponse;

    /**
     * Create transaction for payment intent.
     *
     * @param  array<string,mixed>  $meta
     *
     * @throws BadMethodCallException
     */
    public function createIntentTransaction(Cart $cart, PaymentIntent $paymentIntent, array $meta = []): Transaction
    {
        if ($cart->hasCompletedOrders()) {
            throw new BadMethodCallException('Cannot create transaction for completed order.');
        }

        if (! $cart->draftOrder) {
            throw new BadMethodCallException('Cart has no order.');
        }

        $meta = array_merge($paymentIntent->meta, $meta);

        $data = new TransactionData(
            order_id: $cart->draftOrder->id,
            success: true,
            type: TransactionType::INTENT->value,
            driver: $this->getDriver(),
            amount: $paymentIntent->getAmount(),
            reference: $paymentIntent->getId(),
            status: $paymentIntent->getStatus(),
            card_type: $this->getType(),
        );

        return (new CreateTransaction)($data);
    }
}
