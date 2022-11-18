<?php

namespace Dystcz\LunarApi\Domain\Media\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;

class MediaResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        return [
            'url' => $this->getFullUrl(),
        ];
    }
}
