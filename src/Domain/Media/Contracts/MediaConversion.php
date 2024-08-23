<?php

namespace Dystcz\LunarApi\Domain\Media\Contracts;

use Dystcz\LunarApi\Domain\Media\Data\ConversionOptions;
use Spatie\MediaLibrary\HasMedia;

interface MediaConversion
{
    /**
     * Apply conversions to a model.
     */
    public function apply(HasMedia $model): void;

    /**
     * Get conversions.
     *
     * @return array<int, ConversionOptions>
     */
    public static function conversions(): array;
}
