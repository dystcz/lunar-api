<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Http\Resources;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class JsonApiResource extends \TiMacDonald\JsonApi\JsonApiResource
{
    /**
     * Helper method to create a single resource.
     *
     * @param  class-string<JsonApiResource>  $resourceClass
     * @param  string  $key
     * @return Closure
     */
    public function optionalResource(string $resourceClass, string $key): Closure
    {
        return fn () => optional($this->$key, fn (Model $model) => $resourceClass::make($model));
    }

    /**
     * Helper method to create a resoruce collection.
     *
     * @param  class-string<JsonApiResource>  $resourceClass
     * @param  string  $key
     * @return Closure
     */
    public function optionalCollection(string $resourceClass, string $key): Closure
    {
        return fn () => optional($this->$key, fn (Collection $model) => $resourceClass::collection($model));
    }
}
