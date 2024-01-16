<?php

namespace Dystcz\LunarApi\Domain\Filters\JsonApi\V1;

use Dystcz\LunarApi\Domain\Filters\Entities\Filter;
use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\NonEloquent\Fields\Attribute;

class FilterSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Filter::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return [
            ID::make(),

            Attribute::make('handle'),
            Attribute::make('data_type'),
            Attribute::make('name'),
            Attribute::make('positon'),
            ArrayHash::make('options'),
            ArrayHash::make('meta'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function authorizable(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function repository(): FilterRepository
    {
        return FilterRepository::make()
            ->withServer($this->server)
            ->withSchema($this);
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'filters';
    }
}
