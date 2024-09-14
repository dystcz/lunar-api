<?php

namespace Dystcz\LunarApi\Domain\Collections\Contracts;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Lunar\Models\Contracts\Collection as LunarCollection;

interface Collection extends LunarCollection, Translatable {}
