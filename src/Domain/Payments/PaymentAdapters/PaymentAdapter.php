<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

use BadMethodCallException;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent;
use Dystcz\LunarApi\Domain\Payments\Enums\TransactionType;
use Dystcz\LunarApi\Domain\Transactions\Actions\CreateTransaction;
use Dystcz\LunarApi\Domain\Transactions\Data\TransactionData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\MessageBag;
use Lunar\Exceptions\Carts\CartException;
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
     * @param  array<string,mixed>  $meta */
    abstract public function createIntent(Cart $cart, array $meta = [], ?int $amount = null): PaymentIntent;

    /**
     * Handle incoming webhook call.
     */
    abstract public function handleWebhook(Request $request): JsonResponse;

    /**
     * Create transaction.
     *
     * @param  array<string,mixed>  $meta
     *
     * @throws BadMethodCallException
     */
    public function createTransaction(
        Cart|Order $model,
        TransactionType|string $type,
        string $reference,
        string $status,
        bool $success = false,
        ?int $amount = null,
        ?int $parentId = null,
        array $meta = [],
    ): Transaction {

        if (! $order = $model instanceof Cart ? $this->getOrCreateOrder($model) : $model) {
            throw new CartException(new MessageBag(['Cart has no order.']));
        }

        $data = $this
            ->getTransactionData(
                order: $order,
                type: $type,
                amount: $amount ?? $order->total->value,
                reference: $reference,
                status: $status,
                meta: $meta,
            )
            ->setParentId($parentId)
            ->setSuccess($success);

        return (new CreateTransaction)($data);
    }

    /**
     * Create transaction for payment intent.
     *
     * @param  array<string,mixed>  $meta
     *
     * @throws BadMethodCallException
     */
    public function createIntentTransaction(Cart $cart, PaymentIntent $paymentIntent, array $meta = []): Transaction
    {
        if (! $order = $this->getOrCreateOrder($cart)) {
            throw new CartException(new MessageBag(['Cart has no order.']));
        }

        $data = $this
            ->getTransactionDataForIntent(
                order: $order,
                paymentIntent: $paymentIntent,
                meta: $meta,
            )
            ->setSuccessful();

        return (new CreateTransaction)($data);
    }

    /**
     * Get transaction data.
     *
     * @param  array<string,mixed>  $meta
     *
     * @throws BadMethodCallException
     */
    protected function getTransactionData(
        Order $order,
        TransactionType|string $type,
        int $amount,
        string $reference,
        string $status,
        array $meta = [],
    ): TransactionData {
        return new TransactionData(
            order_id: $order->getRouteKey(),
            type: $type instanceof TransactionType ? $type->value : $type,
            driver: $this->getDriver(),
            amount: $amount,
            reference: $reference,
            status: $status,
            card_type: $this->getType(),
            meta: $meta,
        );
    }

    /**
     * Get transaction data.
     *
     * @param  array<string,mixed>  $meta
     *
     * @throws BadMethodCallException
     */
    protected function getTransactionDataForIntent(
        Order $order,
        PaymentIntent $paymentIntent,
        array $meta = [],
    ): TransactionData {
        return new TransactionData(
            order_id: $order->getRouteKey(),
            type: TransactionType::INTENT->value,
            driver: $this->getDriver(),
            amount: $paymentIntent->getAmount(),
            reference: $paymentIntent->getId(),
            status: $paymentIntent->getStatus(),
            card_type: $this->getType(),
            meta: array_merge($paymentIntent->getMeta(), $meta),
        );
    }

    /**
     * Get or create order from cart.
     *
     * @throws \Lunar\Exceptions\DisallowMultipleCartOrdersException
     */
    protected function getOrCreateOrder(Cart $cart): Order
    {
        $allowMultiple = Config::get('lunar-api.general.checkout.multiple_orders_per_cart', false);

        return $this->getOrderFromCart($cart) ?: $cart->createOrder(allowMultipleOrders: $allowMultiple);
    }

    /**
     * Get order from cart.
     */
    protected function getOrderFromCart(Cart $cart): ?Order
    {
        return $cart->draftOrder ?: $cart->completedOrder;
    }
}
