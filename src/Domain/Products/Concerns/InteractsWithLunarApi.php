<?php

namespace Dystcz\LunarApi\Domain\Products\Concerns;

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\Products\Actions\IsPurchasable;
use Dystcz\LunarApi\Domain\Products\Builders\ProductBuilder;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductTypes\Models\ProductType;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as Attr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Config;
use Lunar\Models\Attribute;
use Lunar\Models\ProductOptionValue;
use Lunar\Models\ProductVariant;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

trait InteractsWithLunarApi
{
    use HashesRouteKey;
    use HasRelationships;
    use InteractsWithAvailability;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return ProductBuilder|static
     */
    public function newEloquentBuilder($query): Builder
    {
        return new ProductBuilder($query);
    }

    /**
     * Get purchaseble attribute.
     */
    public function isPurchasable(): Attr
    {
        return Attr::make(
            get: fn () => (new IsPurchasable)($this),
        );
    }

    /**
     * Get the mapped attributes relation.
     */
    public function attributes(): MorphToMany
    {
        /** @var Product $this */
        $prefix = Config::get('lunar.database.table_prefix');

        if ($this->relationLoaded('productType')) {
            return $this->productType->mappedAttributes();
        }

        $relation = new MorphToMany(
            Attribute::modelClass()::query(),
            new ProductType(['id' => $this->product_type_id]),
            'attributable',
            "{$prefix}attributables",
            'attributable_id',
            'attribute_id',
            'id',
            'id',
            'attributes',
            false,
        );

        return $relation->withTimestamps();
    }

    /**
     * Get prices through variants.
     */
    public function prices(): HasManyThrough
    {
        /** @var Product $this */
        return $this
            ->hasManyThrough(
                Price::modelClass(),
                ProductVariant::modelClass(),
                'product_id',
                'priceable_id'
            )
            ->where(
                'priceable_type',
                (new (ProductVariant::modelClass()))->getMorphClass()
            );
    }

    /**
     * Lowest price relation.
     */
    public function lowestPrice(): HasOneThrough
    {
        /** @var Product $this */
        $pricesTable = $this->prices()->getModel()->getTable();
        $variantsTable = $this->variants()->getModel()->getTable();

        return $this
            ->hasOneThrough(
                Price::modelClass(),
                ProductVariant::modelClass(),
                'product_id',
                'priceable_id'
            )
            ->where($pricesTable.'.id', function ($query) use ($variantsTable, $pricesTable) {
                $query->select($pricesTable.'.id')
                    ->from($pricesTable)
                    ->where('priceable_type', (new (ProductVariant::modelClass()))->getMorphClass())
                    ->whereIn('priceable_id', function ($query) use ($variantsTable) {
                        $query->select('variants.id')
                            ->from($variantsTable.' as variants')
                            ->whereRaw("variants.product_id = {$variantsTable}.product_id");
                    })
                    ->orderBy($pricesTable.'.price', 'asc')
                    ->limit(1);
            });
    }

    /**
     * Highest price relation.
     */
    public function highestPrice(): HasOneThrough
    {
        /** @var Product $this */
        $pricesTable = $this->prices()->getModel()->getTable();
        $variantsTable = $this->variants()->getModel()->getTable();

        return $this
            ->hasOneThrough(
                Price::modelClass(),
                ProductVariant::modelClass(),
                'product_id',
                'priceable_id'
            )
            ->where($pricesTable.'.id', function ($query) use ($variantsTable, $pricesTable) {
                $query->select($pricesTable.'.id')
                    ->from($pricesTable)
                    ->where('priceable_type', (new (ProductVariant::modelClass()))->getMorphClass())
                    ->whereIn('priceable_id', function ($query) use ($variantsTable) {
                        $query->select('variants.id')
                            ->from($variantsTable.' as variants')
                            ->whereRaw("variants.product_id = {$variantsTable}.product_id");
                    })
                    ->orderBy($pricesTable.'.price', 'desc')
                    ->limit(1);
            });
    }

    /**
     * Cheapest variant relation.
     */
    public function cheapestVariant(): HasOne
    {
        /** @var Product $this */
        $pricesTable = $this->prices()->getModel()->getTable();
        $variantsTable = $this->variants()->getModel()->getTable();

        return $this
            ->hasOne(ProductVariant::modelClass())
            ->where($variantsTable.'.id', function ($query) use ($variantsTable, $pricesTable) {
                $query
                    ->select('variants.id')
                    ->from($variantsTable.' as variants')
                    ->join($pricesTable, function ($join) {
                        $join->on('priceable_id', '=', 'variants.id')
                            ->where('priceable_type', (new (ProductVariant::modelClass()))->getMorphClass());
                    })
                    ->whereRaw("variants.product_id = {$variantsTable}.product_id")
                    ->orderBy($pricesTable.'.price', 'asc')
                    ->limit(1);
            });
    }

    /**
     * Most expensive variant relation.
     */
    public function mostExpensiveVariant(): HasOne
    {
        /** @var Product $this */
        $pricesTable = $this->prices()->getModel()->getTable();
        $variantsTable = $this->variants()->getModel()->getTable();

        return $this
            ->hasOne(ProductVariant::modelClass())
            ->where($variantsTable.'.id', function ($query) use ($variantsTable, $pricesTable) {
                $query
                    ->select('variants.id')
                    ->from($variantsTable.' as variants')
                    ->join($pricesTable, function ($join) {
                        $join->on('priceable_id', '=', 'variants.id')
                            ->where('priceable_type', (new (ProductVariant::modelClass()))->getMorphClass());
                    })
                    ->whereRaw("variants.product_id = {$variantsTable}.product_id")
                    ->orderBy($pricesTable.'.price', 'desc')
                    ->limit(1);
            });
    }

    /**
     * Get base prices through variants.
     */
    public function basePrices(): HasManyThrough
    {
        /** @var Product $this */
        return $this
            ->prices()
            ->where('tier', 1)
            ->where('customer_group_id', null);
    }

    /**
     * @return HasManyDeep<ProductOptionValue,Product>
     */
    public function variantValues(): HasManyDeep
    {
        /** @var Product $this */
        $variantsTable = $this->variants()->getModel()->getTable();
        $prefix = Config::get('lunar.database.table_prefix');

        return $this->hasManyDeepFromRelations(
            $this->variants(),
            (new ProductVariant)->values(),
        );
    }
}
