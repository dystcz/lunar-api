<?php

namespace Dystcz\LunarApi\Domain\Media\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends JsonApiResource
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

        return [
            'path' => "{$model->id}/{$model->file_name}",
            'url' => $model->getFullUrl(),
            'file_name' => $model->file_name,
            'mime_type' => $model->mime_type,
            'size' => $model->size,
            'collection_name' => $model->collection_name,
            'order_column' => $model->order_column,
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
            //
        ];
    }
}
