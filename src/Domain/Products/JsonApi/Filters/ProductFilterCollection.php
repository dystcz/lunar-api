<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Filters;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\FilterCollection;
use Illuminate\Support\Collection;
use LaravelJsonApi\Eloquent\Filters\Where;
use Lunar\Models\Attribute;
use Lunar\Models\Contracts\Attribute as AttributeContract;
use Lunar\Models\Product;

class ProductFilterCollection implements FilterCollection
{
    /**
     * Get filters to be registered.
     */
    public function toArray(): array
    {
        /** @var Collection<AttributeContract> $attributes */
        $attributes = Attribute::modelClass()::query()
            ->where('attribute_type', Product::modelClass())
            ->where('filterable', true)
            ->get();

        $filters = $attributes->map(function (AttributeContract $attribute) {
            return match (true) {
                $this->hasConfigKey($attribute, 'richtext') => Where::make($attribute->handle),
                $this->hasConfigKey($attribute, 'on_value') => AttributeBoolFilter::make($attribute->handle),
                $this->hasConfigKey($attribute, 'lookups') => AttributeWhereInFilter::make($attribute->handle)->delimiter(','),
                default => Where::make($attribute->handle),
            };
        });

        return [
            ...$filters->toArray(),
        ];
    }

    /**
     * Check if Attribute has certain config key.
     */
    protected function hasConfigKey(AttributeContract $attribute, string $key): bool
    {
        return array_key_exists($key, $attribute->configuration->toArray());
    }
}
