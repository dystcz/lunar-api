<?php

namespace Dystcz\LunarApi\Domain\AttributeGroups\Contracts;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Lunar\Models\Contracts\AttributeGroup as LunarAttributeGroup;

interface AttributeGroup extends LunarAttributeGroup, Translatable {}
