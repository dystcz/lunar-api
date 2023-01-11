<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Filters;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\FilterCollection;
use LaravelJsonApi\Eloquent\Filters\Where;
use Lunar\Models\Attribute;
use Lunar\Models\Product;

class ProductFilterCollection implements FilterCollection
{
    /**
     * Get filters to be registered.
     *
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Cache
        $attributes = Attribute::query()
            ->where('attribute_type', Product::class)
            ->where('filterable', true)
            ->get();

        $filters = $attributes->map(function (Attribute $attribute) {
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
     *
     * @param Attribute $attribute
     * @param string $key
     * @return bool
     */
    protected function hasConfigKey(Attribute $attribute, string $key): bool
    {
        return array_key_exists($key, $attribute->configuration->toArray());
    }
}
