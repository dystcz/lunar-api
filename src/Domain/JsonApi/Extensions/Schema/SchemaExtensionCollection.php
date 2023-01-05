<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\ExtensionCollection;
use Illuminate\Support\Collection;

class SchemaExtensionCollection extends Collection implements ExtensionCollection
{
    public function toSchemaArray(): array
    {
        return $this->all();
    }
}
