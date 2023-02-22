<?php

namespace Dystcz\LunarApi\Domain\Countries\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use Lunar\Models\Country;

class CountrySchema extends Schema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Country::class;

    /**
     * The relationships that should always be eager loaded.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            ...parent::with(),
        ];
    }

    /**
     * Get the include paths supported by this resource.
     *
     * @return string[]|iterable
     */
    public function includePaths(): iterable
    {
        return [
            ...parent::includePaths(),
        ];
    }

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ...parent::fields(),

            ID::make(),
        ];
    }

    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'countries';
    }
}
