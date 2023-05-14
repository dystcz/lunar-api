<?php

namespace Dystcz\LunarApi\Domain\Media\Contracts;

use Spatie\MediaLibrary\HasMedia;

interface MediaConversion
{
    /**
     * Apply conversions to a model.
     */
    public function apply(HasMedia $model): void;

    /**
     * Get conversions.
     */
    public static function conversions(): array;
}
