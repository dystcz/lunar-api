<?php

namespace Dystcz\LunarApi\Tests\Stubs\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Servers\Server as BaseServer;
use Dystcz\LunarApi\Tests\Stubs\Users\JsonApi\V1\UserSchema;

class Server extends BaseServer
{
    /**
     * Get the server's list of schemas.
     */
    protected function allSchemas(): array
    {
        return [
            UserSchema::class,
        ];
    }
}
