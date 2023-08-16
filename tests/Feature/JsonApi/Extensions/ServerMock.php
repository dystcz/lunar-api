<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extensions;

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
