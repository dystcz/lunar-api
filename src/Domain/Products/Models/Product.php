<?php

namespace Dystcz\LunarApi\Domain\Products\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Config;
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

    public function lowestPrice(): HasOneThrough
    {
        $pricesTable = $this->prices()->getModel()->getTable();
        $variantsTable = $this->variants()->getModel()->getTable();

        return $this
            ->hasOneThrough(
                Price::class,
                ProductVariant::class,
                'product_id',
                'priceable_id'
            )
            ->where($pricesTable . '.id', function ($query) use ($variantsTable, $pricesTable) {
                $query->select($pricesTable . '.id')
                    ->from($pricesTable)
                    ->where('priceable_type', ProductVariant::class)
                    ->whereIn('priceable_id', function ($query) use ($variantsTable) {
                        $query->select('variants.id')
                            ->from($variantsTable . ' as variants')
                            ->whereRaw('variants.product_id = ' . $variantsTable . '.product_id');
                    })
                    ->orderBy($pricesTable . '.price', 'asc')
                    ->limit(1);
            });
    }

    public function cheapestVariant(): HasOne
    {
        $pricesTable = $this->prices()->getModel()->getTable();
        $variantsTable = $this->variants()->getModel()->getTable();

        return $this
            ->hasOne(ProductVariant::class)
            ->where($variantsTable . '.id', function ($query) use ($variantsTable, $pricesTable) {
                $query
                    ->select('variants.id')
                    ->from($variantsTable . ' as variants')
                    ->join($pricesTable, function ($join) {
                        $join->on('priceable_id', '=', 'variants.id')
                            ->where('priceable_type', ProductVariant::class);
                    })
                    ->whereRaw('variants.product_id = ' . Config::get('lunar.database.table_prefix') . 'product_variants.product_id')
                    ->orderBy($pricesTable . '.price', 'asc')
                    ->limit(1);
            });
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
