<?php

namespace Dystcz\LunarApi\Domain\Auth\JsonApi\V1;

use Dystcz\LunarApi\Domain\Auth\JsonApi\Proxies\AuthUser;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\ProxySchema;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\UserSchema;
use Illuminate\Support\Facades\App;

class AuthSchema extends ProxySchema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = AuthUser::class;

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        $userSchema = App::make(UserSchema::class);

        return [
            ...$userSchema->fields(),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'auth';
    }

    /**
     * Determine if the resource is authorizable.
     */
    public function authorizable(): bool
    {
        return false;
    }
}
