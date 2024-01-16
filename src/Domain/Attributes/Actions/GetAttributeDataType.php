<?php

namespace Dystcz\LunarApi\Domain\Attributes\Actions;

use Dystcz\LunarApi\Domain\Attributes\Models\Attribute;
use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Support\Str;

class GetAttributeDataType extends Action
{
    /**
     * Get data type key from an attribute.
     */
    public function handle(Attribute $attribute): string
    {
        return Str::of(class_basename($attribute->type))
            ->snake()
            ->lower();
    }
}
