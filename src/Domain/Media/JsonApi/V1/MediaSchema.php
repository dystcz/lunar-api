<?php

namespace Dystcz\LunarApi\Domain\Media\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Media::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('path')->extractUsing(
                static fn (Media $model) => $model->getPath()
            ),

            Str::make('url')->extractUsing(
                static fn (Media $model) => $model->getFullUrl()
            ),

            Str::make('file_name'),
            Str::make('mime_type'),
            Str::make('size'),
            Str::make('order_column'),

            Str::make('position')->extractUsing(
                static fn (Media $model) => $model->getCustomProperty('position', 0)
            ),

            ArrayHash::make('custom_properties'),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function authorizable(): bool
    {
        return false; // TODO: create policies
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'media';
    }
}
