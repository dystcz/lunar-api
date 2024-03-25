<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Data;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Lunar\Base\Purchasable;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart;
use Lunar\Models\TaxClass;

class PaymentOption implements Purchasable
{
    public bool $default;

    public function __construct(
        public string $name,
        public string $description,
        public string $identifier,
        public string $driver,
        public Price $price,
        public TaxClass $taxClass,
        public ?string $taxReference = null,
        public ?string $option = null,
        public bool $collect = false,
        public ?array $meta = null,
        public ?Closure $modifyCart = null
    ) {
        $this->default = $this->isDefault();
    }

    /**
     * Modify the cart during pipeline execution.
     *
     * @param  Closure(Cart, PaymentOption): Cart  $closure
     */
    public function modifyCartUsing(Closure $closure): self
    {
        $this->modifyCart = $closure;

        return $this;
    }

    /**
     * Determine wether this payment option is default.
     */
    private function isDefault(): bool
    {
        return $this->driver === Config::get('lunar.payments.default');
    }

    /**
     * Get  payment driver.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get price.
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * Get prices.
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
     * Get tax class.
     */
    public function getTaxClass(): TaxClass
    {
        return $this->taxClass;
    }

    /**
     * Get tax reference.
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
