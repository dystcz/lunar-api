<?php

namespace Dystcz\LunarApi\Domain\Brands\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\SchemaType;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIn;
use LaravelJsonApi\Eloquent\Resources\Relation;
use Lunar\Models\Contracts\Brand;
use Lunar\Models\Contracts\Url;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BrandSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Brand::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'default_url',
            'urls',
            'thumbnail',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            $this->idField(),

            Str::make('name'),

            HasOne::make('default_url', 'defaultUrl')
                ->type(SchemaType::get(Url::class))
                ->retainFieldName(),

            HasOne::make('thumbnail')
                ->type(SchemaType::get(Media::class)),

            HasMany::make('urls')
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this)->delimiter(','),

            Where::make('name'),

            WhereIn::make('names', 'name')->delimiter(','),

            WhereHas::make($this, 'urls', 'url')
                ->singular(),

            WhereHas::make($this, 'urls', 'urls'),

            ...parent::filters(),
        ];
    }
}
