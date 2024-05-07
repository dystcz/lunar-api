<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Config;

class ProductVariantMedia extends Pivot
{
    /**
     * Create a new Eloquent model instance.
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $prefix = Config::get('lunar.database.table_prefix');
        $table = "{$prefix}media_product_variant";

        $this->setTable($table);
    }
}
