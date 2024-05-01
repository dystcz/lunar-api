<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Filters;

use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;

class InStockFilter implements Filter
{
    use DeserializesValue;

    private string $name;

    /**
     * Create a new filter.
     *
     * @return static
     */
    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * CustomFilter constructor.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function key(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function isSingular(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($query, $value)
    {
        $value = in_array($value, [true, 'true', 1, '1']) ? true : false;

        // Check if the filter should be applied based on the $value.
        if (! $value) {
            return $query;
        }

        return $query->whereHas('variants', function ($query) {
            // Variants that are "always" purchasable.
            $query->where('purchasable', 'always')
              // Variants that are purchasable and in stock: purchasable = "in_stock" AND stock > 0.
                ->orWhere(function ($query) {
                    $query->where('purchasable', 'in_stock')
                        ->where('stock', '>', 0);
                })
              // Variants that are available for backorder: purchasable = "backorder" AND backorder > 0.
                ->orWhere(function ($query) {
                    $query->where('purchasable', 'backorder')
                        ->where('backorder', '>', 0);
                });
        });
    }
}
