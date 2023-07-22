<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Lunar\Models\Transaction;

class CreatePaymentIntent
{
    /**
     * @var Cart
     */
    public $cart;

    /**
     * Amount in cents.
     *
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $driver;

    /**
     * @var string
     */
    public $cardType;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var bool
     */
    public $success;

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var Transaction
     */
    public $transaction;

    /**
     * @var object
     */
    public $meta;

    /**
     * Create a new action instance.
     */
    public function __construct(
        Cart $cart,
        object $meta
    ) {
        $this->cart = $cart;
        $this->meta = $meta;
        $this->transaction = Transaction::create([
            'type' => 'intent',
            'order_id' => $cart->order->id,
            'success' => false,
            'status' => 'pending',
            'driver' => 'undefined',
            'amount' => 0,
            'reference' => 'undefined',
            'card_type' => 'undefined',
        ]);
    }

    /**
     * Execute the action.
     *
     * @return array return the payment intent as an array
     */
    abstract public function execute(): array

    /**
     * Create the payment intent.
     */
    protected function createIntent()
    {
        $this->getTransaction()->update([
            'type' => 'intent',
            'order_id' => $this->getOrderId(),
            'driver' => $this->getDriver(),
            'amount' => $this->getAmount(),
            'success' => $this->getSuccess(),
            'reference' => $this->getReference(),
            'status' => 'intent',
            'card_type' => $this->getCardType(),
        ]);

        return $this->response();
    }

    /**
     * Get the meta.
     */
    public function getMeta(): object
    {
        return $this->meta;
    }

    /**
     * Get the transaction.
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    /**
     * Get the response.
     */
    protected function response(): array
    {
        return [
            'success' => $this->getSuccess(),
            'orderId' => $this->getOrderId(),
        ];

    }

    /**
     * Get the amount in cents.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Get the driver.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get the card type.
     */
    public function getCardType(): string
    {
        return $this->cardType;
    }

    /**
     * Get the reference.
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Get the success.
     */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Get the order id.
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * Get the cart.
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * set the amount in cents.
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * set the driver.
     */
    public function setDriver(string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * set the card type.
     */
    public function setCardType(string $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * set the reference.
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * set the success.
     */
    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    /**
     * set the order id.
     */
    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }
}
