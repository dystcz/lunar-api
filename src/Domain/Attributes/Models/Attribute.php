<?php

namespace Dystcz\LunarApi\Domain\Attributes\Models;

use Dystcz\LunarApi\Domain\Attributes\Actions\GetAttributeDataType;
use Dystcz\LunarApi\Domain\Attributes\Actions\GetAttributeFilter;
use Dystcz\LunarApi\Domain\Attributes\Actions\GetAttributeOptions;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Casts\Attribute as EloquentAttribute;
use Lunar\Models\Attribute as LunarAttribute;

class Attribute extends LunarAttribute
{
    use HashesRouteKey;

    /**
     * Get attribute options.
     */
    public function options(): EloquentAttribute
    {
        return EloquentAttribute::make(
            get: fn () => GetAttributeOptions::run($this),
        );
    }

    /**
     * Get attribute filter.
     */
    public function filter(): EloquentAttribute
    {
        return EloquentAttribute::make(
            get: fn () => GetAttributeFilter::run($this),
        );
    }

    /**
     * Get attribute data type.
     */
    public function dataType(): EloquentAttribute
    {
        return EloquentAttribute::make(
            get: fn () => GetAttributeDataType::run($this),
        );
    }
}
