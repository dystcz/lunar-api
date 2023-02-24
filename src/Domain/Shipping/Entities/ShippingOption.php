<?php

namespace Dystcz\LunarApi\Domain\Shipping\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class ShippingOption implements Arrayable
{
    public function __construct(
        public string $id,
        public string $identifier,
        public string $name,
        public string $description,
        public float  $price,
    )
    {
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

    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Create a new shipping option entity from an array.
     */
    public static function fromArray(
        \Lunar\DataTypes\ShippingOption $shippingOption
    ): ShippingOption
    {
        $identifier = Str::slug($shippingOption->identifier);

        return new self(
            id: $identifier,
            identifier: $shippingOption->identifier,
            name: $shippingOption->name,
            description: $shippingOption->description,
            price: $shippingOption->price->decimal,
        );
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}