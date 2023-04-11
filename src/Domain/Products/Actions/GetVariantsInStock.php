<?php

namespace Dystcz\LunarApi\Domain\Products\Actions;

use Illuminate\Support\Collection;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class GetVariantsInStock
{
    /**
     * Get variants of the product that are in stock.
     */
    public function __invoke(Product $product): Collection
    {
        return $product->variants->filter(function (ProductVariant $variant) {
            return $variant->stock > 0;
        });
    }
}
