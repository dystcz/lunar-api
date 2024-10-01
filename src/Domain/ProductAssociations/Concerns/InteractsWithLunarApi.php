<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\Concerns;

use Dystcz\LunarApi\Domain\ProductAssociations\Factories\ProductAssociationFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): ProductAssociationFactory
    {
        return ProductAssociationFactory::new();
    }
}
