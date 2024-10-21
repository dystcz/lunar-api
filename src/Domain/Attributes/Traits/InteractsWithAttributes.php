<?php

namespace Dystcz\LunarApi\Domain\Attributes\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Models\Attribute as LunarAttribute;

trait InteractsWithAttributes
{
    /**
     * Getter to return the class name used with attribute relationships.
     *
     * @return string
     */
    public function attributeType(): Attribute
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */

        return Attribute::make(
            get: fn () => $this->getMorphClass(),
        );
    }

    /**
     * Get the mapped attributes relation.
     *
     * @return HasMany<LunarAttribute,Model>
     */
    public function attributes(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */

        return $this->hasMany(
            LunarAttribute::modelClass(),
            'attribute_type',
            'attribute_type',
        );
    }
}
