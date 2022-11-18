<?php

namespace Dystcz\LunarApi\Domain\Prices\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;

class CurrencyResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
