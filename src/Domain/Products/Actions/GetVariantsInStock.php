<?php

namespace Dystcz\LunarApi\Domain\Products\Actions;

use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Support\Collection;

class GetVariantsInStock
{
    /**
     * Get variants of the product that are in stock.
     */
    public function __invoke(Product $product): Collection
    {
        return $product->variants->filter(function (ProductVariant $variant) {
            return Availability::of($variant) !== Availability::OUT_OF_STOCK;
        });
    }
}
