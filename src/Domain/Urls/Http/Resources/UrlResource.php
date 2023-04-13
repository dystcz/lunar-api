<?php

namespace Dystcz\LunarApi\Domain\Urls\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;

class UrlResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        return [
            'slug' => $this->slug,
        ];
    }
}
