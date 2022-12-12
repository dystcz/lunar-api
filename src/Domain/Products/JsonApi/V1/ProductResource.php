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
            'product_type' => $this->productType->name,
            $this->mergeWhen(
                $model->relationLoaded('productType'),
                fn () => AttributeCollection::make($model->productType->productAttributes)
                    ->mapToAttributeGroups($model)
                    ->toArray()
            ),
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
            $this->relation('default_url'),
            $this->relation('associations'),
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
