<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Models;

use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use InvalidArgumentException;
use Lunar\Models\Price as LunarPrice;
use Lunar\Models\ProductVariant as LunarPoductVariant;

class ProductVariant extends LunarPoductVariant
{
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductVariantFactory
    {
        return ProductVariantFactory::new();
    }

    /**
     * Thumbnail relation.
     */
    public function thumbnail(): MorphOne
    {
        // TODO: Not working, finish
        return $this->product->morphOne(config('media-library.media_model'), 'model')
            ->where(function ($query) {
                $query->where('id', function ($q) {
                    return $q->from('dystore_media_product_variant')
                        ->select('media_id')
                        ->where('product_variant_id', $this->getKey())
                        ->where('primary', true)
                        ->take(1);
                });
            });
    }

    /**
     * Lowest price relation.
     *
     * @throws InvalidArgumentException
     */
    public function lowestPrice(): MorphOne
    {
        return $this
            ->morphOne(
                LunarPrice::class,
                'priceable'
            )->ofMany('price', 'min');
    }
}
