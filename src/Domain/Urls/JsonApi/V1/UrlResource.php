<?php

namespace Dystcz\LunarApi\Domain\Urls\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Lunar\Models\Url;

class UrlResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Url */
        $model = $this->resource;

        return [
            'slug' => $model->slug,
            'default' => $model->default,
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        return [];
    }
}
