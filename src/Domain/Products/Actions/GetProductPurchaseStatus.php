<?php

namespace Dystcz\LunarApi\Domain\Products\Actions;

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Enums\PurchaseStatus;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;

class GetProductPurchaseStatus
{
    public function __construct()
    {
    }

    /**
     * Get purchase status for product.
     */
    public function __invoke(Product $product): PurchaseStatus
    {
        $variantsStatuses = $product->variants->map(
            fn (ProductVariant $variant) => PurchaseStatus::fromProductVariant($variant),
        );

        if ($variantsStatuses->contains(PurchaseStatus::AVAILABLE)) {
            return PurchaseStatus::AVAILABLE;
        }
        if ($variantsStatuses->contains(PurchaseStatus::BACKORDER)) {
            return PurchaseStatus::BACKORDER;
        }

        return PurchaseStatus::OUT_OF_STOCK;
    }
}
