<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\Contracts;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Lunar\Models\Contracts\ProductOption as LunarProductOption;

interface ProductOption extends LunarProductOption, Translatable {}
