<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\ProductAssociations\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\ProductAssociations\Contracts\ProductAssociation as ProductAssociationContract;
use Lunar\Models\Contracts\ProductAssociation as LunarProductAssociationContract;
use Lunar\Models\ProductAssociation as LunarProductAssociation;

#[ReplaceModel(LunarProductAssociationContract::class)]
class ProductAssociation extends LunarProductAssociation implements ProductAssociationContract
{
    use InteractsWithLunarApi;
}
