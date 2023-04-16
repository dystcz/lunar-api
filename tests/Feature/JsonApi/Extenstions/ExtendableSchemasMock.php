<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;

class ExtendableSchemasMock extends ProductSchema
{
    protected array $with = [
        'something',
    ];

    protected array $showRelated = [
        'one',
    ];

    protected array $showRelationships = [
        'apple',
    ];

    public function includePaths(): array
    {
        return [
            'include_one',
            'include_two',
            ...$this->extension->includePaths(),
        ];
    }

    public function fields(): array
    {
        return [
            Str::make('ahoj'),
            ...$this->extension->fields(),
        ];
    }

    public function filters(): array
    {
        return [
            Where::make('ahoj'),
            ...$this->extension->filters(),
        ];
    }

    public function sortables(): array
    {
        return [
            'ahoj',
            ...$this->extension->sortables(),
        ];
    }
}
