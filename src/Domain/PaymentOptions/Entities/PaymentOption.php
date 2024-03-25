<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Config;

class PaymentOption implements Arrayable
{
    public bool $default;

    public function __construct(
        public string $id,
        public string $driver,
        public string $name,
    ) {
        $this->default = $this->isDefault();
    }

    /**
     * Get name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get payment driver.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get id.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get default.
     */
    public function getDefault(): bool
    {
        return $this->default;
    }

    /**
     * Determine wether this payment option is default.
     */
    private function isDefault(): bool
    {
        return $this->driver === Config::get('lunar.payments.default');
    }

    /**
     * Create a new payment option entity from an array.
     *
     * @param  array<string,mixed>  $paymentOption
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
