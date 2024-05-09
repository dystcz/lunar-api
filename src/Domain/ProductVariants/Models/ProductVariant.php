<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Models;

use Dystcz\LunarApi\Domain\Attributes\Traits\InteractsWithAttributes;
use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Lunar\Base\Traits\HasUrls;
use Lunar\Models\Price as LunarPrice;
use Lunar\Models\ProductVariant as LunarPoductVariant;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @method MorphMany notifications() Get the notifications relation if `lunar-api-product-notifications` package is installed.
 */
class ProductVariant extends LunarPoductVariant
{
    use HashesRouteKey;
    use HasUrls;
    use InteractsWithAttributes;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductVariantFactory
    {
        return ProductVariantFactory::new();
    }

    /**
     * Get availability attribute.
     */
    public function availability(): Attribute
    {
        return Attribute::make(
            get: fn () => Availability::of($this),
        );
    }

    /**
     * In stock approximate quantity attribute.
     */
    public function approximateInStockQuantity(): Attribute
    {
        $threshold = Config::get('lunar-api.general.availability.approximate_in_stock_quantity.threshold', 5);

        if (Config::get('lunar-api.general.availability.display_real_quantity', false)) {
            return Attribute::make(
                get: fn () => $this->inStockQuantity
            );
        }

        $displayRealUnderThreshold = Config::get(
            'lunar-api.general.availability.approximate_in_stock_quantity.display_real_under_threshold',
            true,
        );

        return Attribute::make(
            get: fn () => match (true) {
                ($this->inStockQuantity > $threshold) => __(
                    'lunar-api::availability.stock.quantity_string.more_than',
                    ['quantity' => $threshold],
                ),
                ($this->inStockQuantity <= $threshold) && $displayRealUnderThreshold => $this->inStockQuantity,
                ($this->inStockQuantity <= $threshold) => __(
                    'lunar-api::availability.stock.quantity_string.less_than',
                    ['quantity' => $threshold],
                ),
                default => null,
            }
        );
    }

    /**
     * In stock quantity attribute.
     */
    public function inStockQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => match (true) {
                $this->purchasable === 'backorder' => $this->backorder,
                default => $this->stock,
            }
        );
    }

    /**
     * Thumbnail relation.
     */
    public function thumbnail(): HasOneThrough
    {
        $prefix = Config::get('lunar.database.table_prefix');
        $table = "{$prefix}media_product_variant";

        return $this
            ->hasOneThrough(
                Media::class,
                ProductVariantMedia::class,
                'product_variant_id',
                'id',
                'id',
                'media_id'
            )
            ->where('primary', true);
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
            )
            ->ofMany('price', 'min');
    }

    /**
     * Highest price relation.
     *
     * @throws InvalidArgumentException
     */
    public function highestPrice(): MorphOne
    {
        return $this
            ->morphOne(
                LunarPrice::class,
                'priceable'
            )
            ->ofMany('price', 'max');
    }

    /**
     * Other variants relation.
     */
    public function otherVariants(): HasMany
    {
        return $this
            ->hasMany(LunarPoductVariant::class, 'product_id', 'product_id')
            ->where($this->getRouteKeyName(), '!=', $this->getAttribute($this->getRouteKeyName()));
    }
}
