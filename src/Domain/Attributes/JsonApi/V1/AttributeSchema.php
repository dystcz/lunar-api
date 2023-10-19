<?php

namespace Dystcz\LunarApi\Domain\Attributes\JsonApi\V1;

use Dystcz\LunarApi\Domain\Attributes\Models\Attribute;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;

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
                ->type('attribute-groups')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks()
                ),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'attributes';
    }
}
