<?php

namespace Dystcz\LunarApi\Domain\AttributeGroups\Models;

use Dystcz\LunarApi\Domain\AttributeGroups\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\AttributeGroups\Contracts\AttributeGroup as AttributeGroupContract;
use Lunar\Models\AttributeGroup as LunarAttributeGroup;

class AttributeGroup extends LunarAttributeGroup implements AttributeGroupContract
{
    use InteractsWithLunarApi;
}
