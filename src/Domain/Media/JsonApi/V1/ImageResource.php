<?php

namespace Dystcz\LunarApi\Domain\Media\JsonApi\V1;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageResource extends MediaResource
{
    /**
     * Get the resource's attributes.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Media $model */
        $model = $this->resource;

        return array_merge(parent::attributes($request), [
            //
        ]);
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
            //
        ];
    }
}
