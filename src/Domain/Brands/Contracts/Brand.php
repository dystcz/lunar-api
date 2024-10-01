<?php

namespace Dystcz\LunarApi\Domain\Brands\Contracts;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Lunar\Models\Contracts\Brand as LunarBrand;

interface Brand extends LunarBrand, Translatable {}
