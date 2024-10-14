<?php

namespace Dystcz\LunarApi\Domain\Products\Contracts;

use Dystcz\LunarApi\Base\Contracts\HasAvailability;
use Dystcz\LunarApi\Base\Contracts\Translatable;
use Lunar\Models\Contracts\Product as LunarProduct;

interface Product extends HasAvailability, LunarProduct, Translatable {}
