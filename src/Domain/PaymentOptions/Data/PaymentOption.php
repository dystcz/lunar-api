<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Data;

use Illuminate\Support\Collection;
use Lunar\Base\Purchasable;
use Lunar\DataTypes\Price;
use Lunar\Models\TaxClass;

class PaymentOption implements Purchasable
{
    public function __construct(
        public string $name,
        public string $description,
        public string $identifier,
        public Price $price,
        public TaxClass $taxClass,
        public ?string $taxReference = null,
        public ?string $option = null,
        public bool $collect = false,
        public ?array $meta = null
    ) {
    }

    /**
     * Get the price for the purchasable item.
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * Get prices for the purchasable item.
     */
    public function getPrices(): Collection
    {
        return Collection::make([
            $this->price,
        ]);
    }

    /**
     * Return the purchasable unit quantity.
     */
    public function getUnitQuantity(): int
    {
        return 1;
    }

    /**
     * Return the purchasable tax class.
     */
    public function getTaxClass(): TaxClass
    {
        return $this->taxClass;
    }

    /**
     * Return the purchasable tax reference.
     */
    public function getTaxReference(): ?string
    {
        return $this->taxReference;
    }

    /**
     * Return what type of purchasable this is, i.e. physical, digital, payment.
     */
    public function getType(): string
    {
        return 'payment';
    }

    /**
     * Return the name for the purchasable.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return the description for the purchasable.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Return the option for this purchasable.
     */
    public function getOption(): ?string
    {
        return $this->option;
    }

    /**
     * Return a unique string which identifies the purchasable item.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Returns whether the purchasable item is shippable.
     */
    public function isShippable(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getThumbnail(): ?string
    {
        return null;
    }
}
