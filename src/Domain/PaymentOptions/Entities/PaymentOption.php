<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Entities;

use Illuminate\Contracts\Support\Arrayable;

class PaymentOption implements Arrayable
{
    public function __construct(
        public string $id,
        public string $driver,
        public string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Create a new payment option entity from an array.
     */
    public static function fromArray(
        array $paymentOption
    ): PaymentOption {
        return new self(
            id: $paymentOption['driver'],
            driver: $paymentOption['driver'],
            name: $paymentOption['name'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'driver' => $this->driver,
        ];
    }
}
