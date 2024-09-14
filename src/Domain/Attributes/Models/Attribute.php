<?php

namespace Dystcz\LunarApi\Domain\Attributes\Models;

use Dystcz\LunarApi\Domain\Attributes\Contracts\Attribute as AttributeContract;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Attribute as LunarAttribute;

class Attribute extends LunarAttribute implements AttributeContract
{
    use HashesRouteKey;
}
