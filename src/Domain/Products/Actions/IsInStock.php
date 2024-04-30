<?php

namespace Dystcz\LunarApi\Domain\Products\Actions;

use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Support\Facades\Cache;

class IsInStock
{
    /**
     * Determine if at least one variant of the product is available.
     */
    public function __invoke(Product $product, bool $skipCache = false): bool
    {
        if ($skipCache) {
            return $this->atLeastOneVariantInStock($product);
        }

        return Cache::remember(
            "product-{$product->id}-in-stock",
            3600,
            fn () => $this->atLeastOneVariantInStock($product),
        );
    }

    /**
     * Determine if at least one variant of the product is available.
     */
    protected function atLeastOneVariantInStock(Product $product): bool
    {
        return $product->variants->reduce(function (bool $carry, ProductVariant $variant) {
            return $carry || in_array(Availability::of($variant)->purchasable());
        }, false);
    }
}
