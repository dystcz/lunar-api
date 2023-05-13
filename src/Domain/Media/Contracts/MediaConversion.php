<?php

namespace Dystcz\LunarApi\Domain\Media\Contracts;

use Lunar\Base\BaseModel;

interface MediaConversion
{
    /**
     * Apply conversions to a model.
     */
    public function apply(BaseModel $model): void;

    /**
     * Get conversions.
     */
    public static function conversions(): array;
}
