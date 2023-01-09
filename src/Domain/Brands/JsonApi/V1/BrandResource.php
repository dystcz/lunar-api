<?php

namespace Dystcz\LunarApi\Domain\Brands\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Lunar\Models\Brand;

class BrandResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Brand $model */
        $model = $this->resource;

        return [
            'name' => $model->name,
            ...ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        /** @var Brand $model */
        $model = $this->resource;

        return [
            $this->relation('default_url'),

            $this
                ->relation('thumbnail')
                ->withoutLinks(),

            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
