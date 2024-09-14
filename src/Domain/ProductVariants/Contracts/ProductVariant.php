<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Contracts;

use Dystcz\LunarApi\Base\Contracts\HasAvailability;
use Lunar\Models\Contracts\ProductVariant as LunarProductVariant;
use Spatie\MediaLibrary\HasMedia;

interface ProductVariant extends HasAvailability, HasMedia, LunarProductVariant {}
