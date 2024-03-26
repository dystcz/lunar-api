<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Filters;

use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasColumn;

class InStockFilter implements Filter
{
    use DeserializesValue;
    use HasColumn;

    /**
     * Create a new filter.
     *
     * @return static
     */
    public static function make(): self
    {
        return new static();
    }

    /**
     * CustomFilter constructor.
     */
    public function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function key(): string
    {
        return 'in_stock';
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
        // Check if the filter should be applied based on the $value.
        if ($value === 'true') {
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

        return $query;
    }
}
