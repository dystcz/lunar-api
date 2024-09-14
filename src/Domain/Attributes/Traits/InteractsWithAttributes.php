<?php

namespace Dystcz\LunarApi\Domain\Attributes\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Models\Attribute as LunarAttribute;

trait InteractsWithAttributes
{
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
            'attribute_classname',
        );
    }
}
