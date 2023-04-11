<?php

namespace Dystcz\LunarApi\Domain\Products\Actions;

use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class IsInStock
{
    /**
     * Determine if at least one variant of the product is in stock.
     */
    public function __invoke(Product $product): bool
    {
        return $product->variants->reduce(function (bool $carry, ProductVariant $variant) {
            return $carry || $variant->stock > 0;
        }, false);
    }
}
