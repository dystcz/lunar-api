<?php

namespace Dystcz\LunarApi\Domain\Media\Http\Resources;

use Illuminate\Support\Facades\App;
use Dystcz\LunarApi\Domain\JsonApi\Builders\MediaJsonApiBuilder;
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
            'path' => "{$model->id}/{$model->file_name}",
            'url' => $model->getFullUrl(),
            'file_name' => $model->file_name,
            'mime_type' => $model->mime_type,
            'size' => $model->size,
            'collection_name' => $model->collection_name,
            'order_column' => $model->order_column,
        ];
    }

    protected function toRelationships(Request $request): array
    {
        return App::get(MediaJsonApiBuilder::class)->toRelationships($this->resource);
    }
}
