<?php

namespace Dystcz\LunarApi\Domain\Media\Contracts;

use Dystcz\LunarApi\Domain\Media\Data\ConversionOptions;
use Lunar\Base\MediaDefinitionsInterface;
use Spatie\MediaLibrary\HasMedia;

interface MediaDefinitions extends MediaDefinitionsInterface
{
    /**
     * Apply conversions to a model.
     */
    public static function applyConversions(HasMedia $model): void;

    /**
     * Get conversions.
     *
     * @return array<int, ConversionOptions>
     */
    public static function conversions(): array;
}
