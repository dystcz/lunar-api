<?php

namespace Dystcz\LunarApi\Domain\Attributes\Collections;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AttributeCollection extends EloquentCollection
{
    /**
     * Map Attributes into groups based on AttributeGroup they are part of.
     *
     * @param  Model  $model
     * @return Collection
     */
    public function mapToAttributeGroups(Model $model): Collection
    {
        return $this
            ->filter(fn ($attribute) => $model->attr($attribute->handle))
            ->groupBy(
                fn ($attribute) => $attribute->attributeGroup->handle
            )
            ->map(
                function ($attributes, $group) use ($model) {
                    if (in_array($group, ['product_properties'])) {
                        return $attributes->map(
                            fn ($attribute) => [
                                'key' => $attribute->handle,
                                'name' => $attribute->translate('name'),
                                'value' => $model->attr($attribute->handle),
                            ]
                        );
                    }

                    return $attributes->mapWithKeys(
                        fn ($attribute) => [$attribute->handle => $model->attr($attribute->handle)]
                    );
                }
            );
    }
}
