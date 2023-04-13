<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

use LaravelJsonApi\Core\Server\Server;

class ServerMock extends Server
{
    public function allSchemas(): array
    {
        return [
            ExtendableSchemasMock::class,
        ];
    }
}
