<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Models;

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Lunar\Models\ProductVariant as LunarPoductVariant;

class ProductVariant extends LunarPoductVariant
{
    public function lowestPrice(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(
            \Lunar\Models\Price::class,
            'priceable'
        )->ofMany('price', 'min');
    }
}
