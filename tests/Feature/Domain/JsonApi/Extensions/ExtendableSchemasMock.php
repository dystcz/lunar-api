<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\JsonApi\Extensions;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use Lunar\Models\Contracts\Product;

class ExtendableSchemasMock extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Product::class;

    public static function resource(): string
    {
        return ProductResourceMock::class;
    }

    protected array $with = [
        'something',
    ];

    protected array $showRelated = [
        'one',
    ];

    protected array $showRelationship = [
        'apple',
    ];

    public function includePaths(): array
    {
        return [
            'include_one',
            'include_two',

            ...parent::includePaths(),
        ];
    }

    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('ahoj'),

            ...parent::fields(),
        ];
    }

    public function filters(): array
    {
        return [
            Where::make('ahoj'),

            ...parent::filters(),
        ];
    }

    public function sortables(): array
    {
        return [
            'ahoj',

            ...parent::sortables(),
        ];
    }

    public static function type(): string
    {
        return 'products';
    }
}
