<?php

namespace Dystcz\LunarApi\Domain\Attributes\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use Lunar\Models\Contracts\Attribute;
use Lunar\Models\Contracts\AttributeGroup;

class AttributeSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Attribute::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'attribute_group',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return [
            $this->idField(),

            BelongsTo::make('attribute_group', 'attributeGroup')
                ->retainFieldName()
                ->type(ModelType::get(AttributeGroup::class))
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks()
                ),

            ...parent::fields(),
        ];
    }
}
