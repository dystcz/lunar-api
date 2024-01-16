<?php

namespace Dystcz\LunarApi\Domain\Filters\Entities;

use Dystcz\LunarApi\Domain\Attributes\Actions\GetAttributeOptions;
use Generator;
use Illuminate\Support\Collection;
use Lunar\Base\FieldType;
use Lunar\Models\Attribute;
use Lunar\Models\Product;

class FilterStorage
{
    private Collection $filters;

    public function __construct()
    {
        $this->filters = $this->getFilters();
    }

    /**
     * Get filters.
     */
    protected function getFilters(): Collection
    {
        $attributes = Attribute::query()
            ->where('attribute_type', Product::class)
            ->whereNotIn('type', [
                \Lunar\FieldTypes\Text::class,
                \Lunar\FieldTypes\TranslatedText::class,
            ])
            // ->where('filterable', true)
            ->get();

        $options = Product::query()
            ->select('attribute_data')
            ->get()
            ->map(
                fn (Product $product) => $product->attribute_data
                    ->filter(fn (FieldType $fieldType, string $handle) => $attributes->contains('handle', $handle))
                    ->map(fn (FieldType $fieldType, string $handle) => $product->attr($handle)),
            )
            ->reduce(function (Collection $carry, Collection $attributes) {
                return $carry->mergeRecursive($attributes);
            }, Collection::make())
            ->map(
                fn (?array $options, string $handle) => Collection::make($options)
                    ->groupBy(fn (mixed $value) => $value)
                    ->filter(fn (Collection $values, string $key) => $key)
                    ->map(fn (Collection $values, string $key) => $values->count())
            )->dd();

        $filters = $attributes->mapWithKeys(function (Attribute $attribute) {
            return [
                $attribute->handle => new Filter(
                    handle: $attribute->handle,
                    data_type: $attribute->data_type,
                    name: $attribute->translate('name'),
                    position: $attribute->position,
                    options: GetAttributeOptions::run($attribute),
                    meta: [],
                ),
            ];
        });

        return $filters;
    }

    /**
     * Find a shipping option.
     */
    public function find(string $handle): ?Filter
    {
        if (isset($this->filters[$handle])) {
            return $this->filters[$handle];
        }

        return null;
    }

    /**
     * @return Generator<Filter>
     */
    public function cursor(): Generator
    {
        foreach ($this->filters as $handle => $filter) {
            yield $filter;
        }
    }

    /**
     * Get all shippingOptions.
     *
     * @return Filter[]
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }
}
