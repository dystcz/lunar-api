<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Filters;

use Dystcz\LunarApi\Domain\Attributes\Actions\GetAttributeFilter;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\FilterCollection;
use Illuminate\Support\Collection;
use Lunar\Models\Attribute;
use Lunar\Models\Product;

class ProductFilters implements FilterCollection
{
    /**
     * Get filters to be registered.
     */
    public function toArray(): array
    {
        return [
            ...$this->getAttributeFilters(),
        ];
    }

    /**
     * Get attribute filters.
     */
    protected function getAttributeFilters(): Collection
    {
        $attributes = Attribute::query()
            ->where('attribute_type', Product::class)
            ->where('filterable', true)
            ->get();

        // ray($attributes, $attributes->pluck('handle'));

        $filters = $attributes->map(
            fn (Attribute $attribute) => GetAttributeFilter::run($attribute),
        );

        return $filters;
    }

    /**
     * Check if Attribute has certain config key.
     */
    protected function hasConfigKey(Attribute $attribute, string $key): bool
    {
        return array_key_exists($key, $attribute->configuration->toArray());
    }
}
