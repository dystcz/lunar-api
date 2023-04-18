<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\Factories;

use Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation;
use Lunar\Database\Factories\ProductAssociationFactory as LunarProductAssociationFactory;

class ProductAssociationFactory extends LunarProductAssociationFactory
{
    protected $model = ProductAssociation::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
