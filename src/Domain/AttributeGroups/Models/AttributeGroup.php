<?php

namespace Dystcz\LunarApi\Domain\AttributeGroups\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\AttributeGroups\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\AttributeGroups\Contracts\AttributeGroup as AttributeGroupContract;
use Lunar\Models\AttributeGroup as LunarAttributeGroup;
use Lunar\Models\Contracts\AttributeGroup as LunarAttributeGroupContract;

#[ReplaceModel(LunarAttributeGroupContract::class)]
class AttributeGroup extends LunarAttributeGroup implements AttributeGroupContract
{
    use InteractsWithLunarApi;
}
