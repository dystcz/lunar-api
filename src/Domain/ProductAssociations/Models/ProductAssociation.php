<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\Models;

use Dystcz\LunarApi\Domain\ProductAssociations\Contracts\ProductAssociation as ProductAssociationContract;
use Dystcz\LunarApi\Domain\ProductAssociations\Factories\ProductAssociationFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\ProductAssociation as LunarProductAssociation;

class ProductAssociation extends LunarProductAssociation implements ProductAssociationContract
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
