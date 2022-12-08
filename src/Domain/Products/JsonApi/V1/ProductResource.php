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

        if ($model->relationLoaded('variants')) {
            $model->variants->each(fn ($variant) => $variant->setRelation('product', $model));
        }

        // dd($model->productType->mappedAttributes, $model->productType->productAttributes);

        return [
            'stock' => $this->stock,
            $this->mergeWhen(
                $model->relationLoaded('productType'),
                fn () => AttributeCollection::make($model->productType->productAttributes)
                    ->mapToAttributeGroups($model)
                    ->toArray()
            ),
            // 'urls_count' => $this->when($model->urls_count, fn () => $model->urls_count),
            // 'images_count' => $this->when($model->images_count, fn () => $model->images_count),
            // 'variants_count' => $this->when($model->variants_count, fn () => $model->variants_count),
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
        /** @var Product */
        $model = $this->resource;

        return [
            $this->relation('brand'),
            $this->relation('urls'),
            $this->relation('defaultUrl'),
            $this
                ->relation('images')
                ->withMeta(array_filter([
                    'count' => $model->images_count,
                ], fn ($value) => null !== $value)),
            $this
                ->relation('variants')
                ->withMeta(array_filter([
                    'count' => $model->variants_count,
                ], fn ($value) => null !== $value)),
        ];
    }
}
