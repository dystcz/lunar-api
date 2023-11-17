<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Models;

use Dystcz\LunarApi\Domain\Attributes\Traits\InteractsWithAttributes;
use Dystcz\LunarApi\Domain\ProductVariants\Enums\PurchaseStatus;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Lunar\Models\Price as LunarPrice;
use Lunar\Models\ProductVariant as LunarPoductVariant;

class ProductVariant extends LunarPoductVariant
{
    use InteractsWithAttributes;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductVariantFactory
    {
        return ProductVariantFactory::new();
    }

    /**
     * Get the product variant base class.
     */
    public function baseModelClass(): string
    {
        return LunarPoductVariant::class;
    }

    /**
     * Get product variant purchase status.
     */
    protected function purchaseStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => PurchaseStatus::fromProductVariant($this),
        );
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
                    $prefix = Config::get('lunar.database.table_prefix');

                    return $q->from("{$prefix}media_product_variant")
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
