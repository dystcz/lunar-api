<?php

namespace Dystcz\LunarApi\Domain\Attributes\Contracts;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Lunar\Models\Contracts\Attribute as LunarAttribute;

interface Attribute extends LunarAttribute, Translatable {}
