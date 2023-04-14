<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Illuminate\Http\Request;

class ProductResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var Product $model */
        $model = $this->resource;

        if ($model->relationLoaded('variants')) {
            $model->variants->each(fn ($variant) => $variant->setRelation('product', $model));
        }

        return parent::attributes($request);
    }

    /**
     * Get the resource's relationships.
     *
     * @param  Request|null  $request
     */
    public function relationships($request): iterable
    {
        /** @var Product $model */
        $model = $this->resource;

        return [
            $this->relation('associations'),

            $this->relation('brand'),

            $this->relation('cheapest_variant'),

            $this
                ->relation('collections')
                ->withoutLinks()
                ->withMeta(
                    array_filter([
                        'count' => $model->collections_count,
                    ], fn ($value) => null !== $value)
                ),

            $this->relation('default_url'),

            $this
                ->relation('images')
                ->withoutLinks()
                ->withMeta(
                    array_filter([
                        'count' => $model->images_count,
                    ], fn ($value) => null !== $value)
                ),

            $this->relation('lowest_price')
                ->withoutLinks(),

            $this->relation('prices')
                ->withoutLinks(),

            $this->relation('tags'),

            $this
                ->relation('thumbnail')
                ->withoutLinks(),

            $this
                ->relation('urls')
                ->withoutLinks(),

            $this
                ->relation('variants')
                ->withMeta(
                    array_filter([
                        'count' => $model->variants_count,
                    ], fn ($value) => null !== $value)
                ),

            ...parent::relationships($request),
        ];
    }
}
