<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\Attributes\Collections\AttributeCollection;
use LaravelJsonApi\Core\Resources\JsonApiResource;
use Lunar\Models\Product;

class ProductResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Product */
        $model = $this->resource;

        return [
            'slug' => $this->when($model->relationLoaded('defaultUrl'), fn () => $model->defaultUrl->slug),
            $this->mergeWhen(
                $model->relationLoaded('productType'),
                fn () => AttributeCollection::make($model->mappedAttributes())
                    ->mapToAttributeGroups($model)
                    ->toArray()
            ),
            'variants_count' => $this->when($model->variants_count, fn () => $model->variants_count),
            'images_count' => $this->when($model->images_count, fn () => $model->images_count),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('media'),
            // $this->relation('comments'),
            // $this->relation('tags'),
        ];
    }
}
