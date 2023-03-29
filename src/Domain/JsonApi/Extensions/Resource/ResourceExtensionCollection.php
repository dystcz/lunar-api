<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\ExtensionCollection;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Support\Collection;

class ResourceExtensionCollection extends Collection implements ExtensionCollection
{
    /**
     * Map data for use in a resource.
     */
    public function toResourceArray(JsonApiResource $resource): array
    {
        return $this->map(fn ($cb) => $cb($resource))->collapse()->all();
    }
}
