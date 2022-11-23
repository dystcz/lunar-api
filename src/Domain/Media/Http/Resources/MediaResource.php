<?php

namespace Dystcz\LunarApi\Domain\Media\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        /** @var Media $model */
        $model = $this->resource;

        return [
            'id' => $model->id,
            'path' => "{$model->id}/{$model->file_name}",
            'url' => $model->getFullUrl(),
            'name' => $model->file_name,
            'type' => $model->mime_type,
            'size' => $model->size,
            'collection_name' => $model->collection_name,
            'order' => $model->order_column,
        ];
    }
}
