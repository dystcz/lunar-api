<?php

namespace Dystcz\LunarApi\Domain\Media\JsonApi\V1;

use Illuminate\Http\Request;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageResource extends MediaResource
{
    /**
     * Get the resource's attributes.
     *
     * @param Request|null $request
     */
    public function attributes($request): iterable
    {
        /** @var Media $model */
        $model = $this->resource;

        return array_merge(parent::attributes($request), [
            ...ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        ]);
    }

    /**
     * Get the resource's relationships.
     *
     * @param Request|null $request
     */
    public function relationships($request): iterable
    {
        return [
            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
