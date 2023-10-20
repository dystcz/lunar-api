<?php

namespace Dystcz\LunarApi\Domain\AttributeGroups\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\AttributeGroup as LunarAttributeGroup;

class AttributeGroup extends LunarAttributeGroup
{
    use HashesRouteKey;
}
