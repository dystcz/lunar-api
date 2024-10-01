<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\Models;

use Dystcz\LunarApi\Domain\ProductAssociations\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductAssociations\Contracts\ProductAssociation as ProductAssociationContract;
use Lunar\Models\ProductAssociation as LunarProductAssociation;

class ProductAssociation extends LunarProductAssociation implements ProductAssociationContract
{
    use InteractsWithLunarApi;
}
