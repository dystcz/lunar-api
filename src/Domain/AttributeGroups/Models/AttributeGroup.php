<?php

namespace Dystcz\LunarApi\Domain\AttributeGroups\Models;

use Dystcz\LunarApi\Domain\AttributeGroups\Contracts\AttributeGroup as AttributeGroupContract;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\AttributeGroup as LunarAttributeGroup;

class AttributeGroup extends LunarAttributeGroup implements AttributeGroupContract
{
    use HashesRouteKey;
}
