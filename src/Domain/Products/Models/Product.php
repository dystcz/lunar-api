<?php

namespace Dystcz\LunarApi\Domain\Products\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Lunar\Models\Price;
use Lunar\Models\Product as LunarProduct;
use Lunar\Models\ProductVariant;

class Product extends LunarProduct
{
    /**
     * Get prices through variants.
     *
     * @return HasManyThrough
     */
    public function prices(): HasManyThrough
    {
        return $this
            ->hasManyThrough(
                Price::class,
                ProductVariant::class,
                'product_id',
                'priceable_id'
            )
            ->where(
                'priceable_type',
                ProductVariant::class
            );
    }

    /**
     * Get base prices through variants.
     *
     * @return HasManyThrough
     */
    public function basePrices(): HasManyThrough
    {
        return $this->prices()->whereTier(1)->whereNull('customer_group_id');
    }
}
