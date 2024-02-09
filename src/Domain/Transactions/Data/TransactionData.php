<?php

namespace Dystcz\LunarApi\Domain\Transactions\Data;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;

class TransactionData implements Arrayable
{
    use Conditionable;

    /**
     * @param  array<string,mixed>  $meta
     */
    public function __construct(
        public int $order_id,
        public string $type,
        public string $driver,
        public float $amount,
        public string $reference,
        public string $status,
        public string $card_type,
        public bool $success = false,
        public ?string $notes = null,
        public ?int $parent_transaction_id = null,
        public ?string $last_four = null,
        public ?Carbon $captured_at = null,
        public array $meta = [],
    ) {
        //
    }

    /**
     * Merge transaction data.
     *
     * @param  array<string,mixed>  $data
     */
    public function mergeData(array $data): self
    {
        $data = array_merge($this->toArray(), $data);

        return new static(...$data);
    }

    /**
     * Set parent transaction id.
     */
    public function setParentId(int $parentId): self
    {
        $this->parent_transaction_id = $parentId;

        return $this;
    }

    /**
     * Set successfull.
     */
    public function setSuccessful(): self
    {
        return $this->setSuccess(true);
    }

    /**
     * Set unsuccessfull.
     */
    public function setUnsuccessful(): self
    {
        return $this->setSuccess(false);
    }

    /**
     * Set success.
     */
    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Set status.
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set error.
     */
    public function setError(string $error): self
    {
        $this->meta = array_merge($this->meta, [
            'error' => $error,
        ]);

        return $this;
    }

    /**
     * Get error.
     */
    public function getError(): ?string
    {
        return $this->meta['error'] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'parent_transaction_id' => $this->parent_transaction_id,
            'order_id' => $this->order_id,
            'success' => $this->success,
            'type' => $this->type,
            'driver' => $this->driver,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'status' => $this->status,
            'card_type' => $this->card_type,
            'notes' => $this->notes,
            'last_four' => $this->last_four,
            'captured_at' => $this->captured_at?->toDateTimeString(),
            'meta' => $this->meta,
        ];
    }
}
