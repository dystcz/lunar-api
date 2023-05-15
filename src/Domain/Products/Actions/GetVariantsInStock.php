<?php

namespace Dystcz\LunarApi\Domain\Products\Actions;

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Enums\PurchaseStatus;
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
            return PurchaseStatus::fromProductVariant($variant) != PurchaseStatus::OUT_OF_STOCK;
        });
    }
}
