<?php

namespace Dystcz\LunarApi\Domain\Payments\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface PaymentIntent extends Arrayable
{
    /**
     * Get ID.
     */
    public function getId(): mixed;

    /**
     * Get amount.
     */
    public function getAmount(): int;

    /**
     * Get status.
     */
    public function getStatus(): string;

    /**
     * Get client secret.
     */
    public function getClientSecret(): string;

    /**
     * Get meta.
     *
     * @return array<string,mixed>
     */
    public function getMeta(): array;
}
