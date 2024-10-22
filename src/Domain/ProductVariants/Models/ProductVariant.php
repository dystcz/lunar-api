<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Base\Contracts\HasAvailability;
use Dystcz\LunarApi\Base\Contracts\Translatable;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductVariants\Contracts\ProductVariant as ProductVariantContract;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Lunar\Models\Contracts\ProductVariant as LunarPoductVariantContract;
use Lunar\Models\ProductVariant as LunarPoductVariant;
use Spatie\MediaLibrary\HasMedia;

/**
 * @method MorphMany notifications() Get the notifications relation if `lunar-api-product-notifications` package is installed.
 */
#[ReplaceModel(LunarPoductVariantContract::class)]
class ProductVariant extends LunarPoductVariant implements HasAvailability, HasMedia, ProductVariantContract, Translatable
{
    use InteractsWithLunarApi;
}
