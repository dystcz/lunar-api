<?php

namespace Dystcz\LunarApi\Domain\AttributeGroups\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Lunar\Models\Contracts\AttributeGroup;

class AttributeGroupSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = AttributeGroup::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return [
            $this->idField(),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'attribute-groups';
    }
}
