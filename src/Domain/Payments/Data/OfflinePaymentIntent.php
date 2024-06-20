<?php

namespace Dystcz\LunarApi\Domain\Payments\Data;

use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent as PaymentIntentContract;
use Dystcz\LunarApi\Domain\Payments\Enums\PaymentIntentStatus;

class OfflinePaymentIntent implements PaymentIntentContract
{
    /**
     * @param  array<string,mixed>  $meta
     */
    public function __construct(
        public int $amount,
        public string $id,
        public ?string $status = null,
        public array $meta = [],
    ) {}

    /**
     * Get ID.
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * Get amount.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Get status.
     */
    public function getStatus(): string
    {
        return $this->status ?? PaymentIntentStatus::SUCCEEDED->value;
    }

    /**
     * Get client secret.
     */
    public function getClientSecret(): string
    {
        return 'offline-secret';
    }

    /**
     * Get meta.
     *
     * @return array<string,mixed>
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'amount' => $this->getAmount(),
            'status' => $this->getStatus(),
            'client_secret' => $this->getClientSecret(),
            'meta' => $this->getMeta(),
        ];
    }
}
