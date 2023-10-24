<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;

class ShippingOption implements Arrayable
{
    public function __construct(
        public string $id,
        public string $identifier,
        public string $name,
        public string $description,
        public Price $price,
        public Currency $currency,
        public array $meta,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * Create a new shipping option entity from an array.
     */
    public static function fromArray(
        \Lunar\DataTypes\ShippingOption $shippingOption
    ): ShippingOption {
        $identifier = Str::slug($shippingOption->identifier);

        return new self(
            id: $identifier,
            identifier: $shippingOption->identifier,
            name: $shippingOption->name,
            description: $shippingOption->description,
            price: $shippingOption->price,
            currency: $shippingOption->price->currency,
            meta: $shippingOption->meta,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'name' => $this->name,
            'description' => $this->description,
            'price' => [
                'decimal' => $this->price->decimal,
                'formatted' => $this->price->formatted,
            ],
            'currency' => $this->currency->toArray(),
            'meta' => $this->meta,
        ];
    }
}
