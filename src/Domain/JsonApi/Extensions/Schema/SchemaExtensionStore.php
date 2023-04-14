<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\ExtensionStore as ExtensionStoreContract;
use Dystcz\LunarApi\Domain\JsonApi\Data\ExtensionStore;

class SchemaExtensionStore extends ExtensionStore implements ExtensionStoreContract
{
    public function __construct(
        public iterable $with = [],
        public iterable $includePaths = [],
        public iterable $fields = [],
        public iterable $filters = [],
        public iterable $sortables = [],
    ) {
    }
}
