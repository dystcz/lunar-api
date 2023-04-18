<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\Models;

use Dystcz\LunarApi\Domain\ProductAssociations\Factories\ProductAssociationFactory;
use Lunar\Models\ProductAssociation as LunarProductAssociation;

class ProductAssociation extends LunarProductAssociation
{
    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): ProductAssociationFactory
    {
        return ProductAssociationFactory::new();
    }
}
