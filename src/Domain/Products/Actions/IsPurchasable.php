<?php

namespace Dystcz\LunarApi\Domain\Products\Actions;

use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Illuminate\Support\Facades\Cache;

class IsPurchasable
{
    /**
     * Determine if at least one variant of the product is available.
     */
    public function __invoke(Product $product, bool $skipCache = false): bool
    {
        if ($skipCache) {
            return Availability::of($product)->purchasable();
        }

        return Cache::remember(
            "product-{$product->id}-purchasable",
            3600,
            fn () => Availability::of($product)->purchasable(),
        );
    }
}
