<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;

class ShippingOption implements Arrayable
{
    /**
     * @param  array<string,mixed>  $meta
     */
    public function __construct(
        public string $id,
        public string $identifier,
        public string $name,
        public string $description,
        public Price $price,
        public Currency $currency,
        public array $meta,
    ) {}

    /**
     * Get name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get id.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get identifier.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get price.
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * Get curency.
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
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
     * Create a new shipping option entity from shipping option data type.
     */
    public static function fromOption(
        \Lunar\DataTypes\ShippingOption $shippingOption
    ): ShippingOption {
        $id = Str::slug($shippingOption->identifier);

        return new self(
            id: $id,
            identifier: $shippingOption->identifier,
            name: $shippingOption->name,
            description: $shippingOption->description,
            price: $shippingOption->price,
            currency: $shippingOption->price->currency,
            meta: $shippingOption->meta,
        );
    }

    /**
     * Cast to array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'identifier' => $this->getIdentifier(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => [
                'decimal' => $this->getPrice()->decimal,
                'formatted' => $this->getPrice()->formatted,
            ],
            'currency' => Arr::only($this->getCurrency()->toArray(), ['code', 'name']),
            'meta' => $this->getMeta(),
        ];
    }
}
