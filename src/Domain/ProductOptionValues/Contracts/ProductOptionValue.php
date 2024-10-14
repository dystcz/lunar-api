<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\Contracts;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Lunar\Models\Contracts\ProductOptionValue as LunarProductOptionValue;

interface ProductOptionValue extends LunarProductOptionValue, Translatable {}
