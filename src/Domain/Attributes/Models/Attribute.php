<?php

namespace Dystcz\LunarApi\Domain\Attributes\Models;

use Dystcz\LunarApi\Domain\Attributes\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Attributes\Contracts\Attribute as AttributeContract;
use Lunar\Models\Attribute as LunarAttribute;

class Attribute extends LunarAttribute implements AttributeContract
{
    use InteractsWithLunarApi;
}
