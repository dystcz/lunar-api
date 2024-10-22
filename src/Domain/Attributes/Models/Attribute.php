<?php

namespace Dystcz\LunarApi\Domain\Attributes\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Attributes\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Attributes\Contracts\Attribute as AttributeContract;
use Lunar\Models\Attribute as LunarAttribute;
use Lunar\Models\Contracts\Attribute as LunarAttributeContract;

#[ReplaceModel(LunarAttributeContract::class)]
class Attribute extends LunarAttribute implements AttributeContract
{
    use InteractsWithLunarApi;
}
