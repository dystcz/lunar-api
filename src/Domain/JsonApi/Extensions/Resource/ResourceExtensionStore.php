<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\ExtensionStore as ExtensionStoreContract;
use Dystcz\LunarApi\Domain\JsonApi\Data\ExtensionStore;

class ResourceExtensionStore extends ExtensionStore implements ExtensionStoreContract
{
    public function __construct(
        public iterable $attributes = [],
        public iterable $relationships = [],
    ) {
    }
}
