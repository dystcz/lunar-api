<?php

namespace Dystcz\LunarApi\Domain\Media\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
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

            ...parent::attributes($request),
        ];
    }
}
