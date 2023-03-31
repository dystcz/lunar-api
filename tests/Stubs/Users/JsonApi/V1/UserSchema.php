<?php

namespace Dystcz\LunarApi\Tests\Stubs\Users\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use LaravelJsonApi\Eloquent\Fields\ID;

class UserSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = User::class;

    /**
     * Get the include paths supported by this resource.
     *
     * @return string[]|iterable
     */
    public function includePaths(): iterable
    {
        return [
            //
        ];
    }

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ID::make(),
        ];
    }

    /**
     * Determine if the resource is authorizable.
     */
    public function authorizable(): bool
    {
        return false;
    }

    /**
     * Get the JSON:API resource type.
     */
    public static function type(): string
    {
        return 'users';
    }
}
