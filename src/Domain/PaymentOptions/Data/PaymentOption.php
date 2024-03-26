<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Data;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Lunar\Base\Purchasable;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class PaymentOption implements Arrayable, Purchasable
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
        $this->setDefault();
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
    private function setDefault(): self
    {
        $this->default = $this->driver === Config::get('lunar.payments.default');

        return $this;
    }

    /**
     * Get default.
     */
    public function isDefault(): bool
    {
        return $this->default === true;
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
     * Get curency.
     */
    public function getCurrency(): Currency
    {
        return $this->getPrice()->currency;
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
     * Return the option for this purchasable.
     */
    public function getOption(): ?string
    {
        return $this->option;
    }

    /**
     * Get id.
     */
    public function getId(): string
    {
        return Str::slug($this->identifier);
    }

    /**
     * Get identifier.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Determine if this purchasable is shippable.
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
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'identifier' => $this->getIdentifier(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'driver' => $this->getDriver(),
            'price' => [
                'decimal' => $this->getPrice()->decimal,
                'formatted' => $this->getPrice()->formatted,
            ],
            'currency' => Arr::only($this->getCurrency()->toArray(), ['code', 'name']),
            'default' => $this->isDefault(),
            'meta' => $this->getMeta(),
        ];
    }
}
