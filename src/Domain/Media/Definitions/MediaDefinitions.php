<?php

namespace Dystcz\LunarApi\Domain\Media\Definitions;

use Domain\Media\Data\ConversionData;
use Dystcz\LunarApi\Domain\Media\Contracts\MediaDefinitions as MediaDefinitionsContract;
use Dystcz\LunarApi\Domain\Media\Data\ConversionOptions;
use Lunar\Base\StandardMediaDefinitions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaDefinitions extends StandardMediaDefinitions implements MediaDefinitionsContract
{
    protected function registerCollectionConversions(MediaCollection $collection, HasMedia $model): void
    {
        $collection->registerMediaConversions(function (Media $media) use ($model) {
            self::applyConversions($model);
        });
    }

    /**
     * Apply conversions to a model.
     */
    public static function applyConversions(HasMedia $model): void
    {
        foreach (self::conversions() as $options) {
            /** @var ConversionOptions $options */
            $model->addMediaConversion($options->key)
                ->when(
                    $options->hasCollections(),
                    fn (Conversion $conversion) => $conversion->performOnCollections($options->collections),
                )
                ->when(
                    ! $options->shouldQueue(),
                    fn (Conversion $conversion) => $conversion->nonQueued(),
                )
                ->when(
                    $options->hasDimensions(),
                    fn (Conversion $conversion) => $conversion->fit(
                        Fit::Fill,
                        $options->width,
                        $options->height,
                    ),
                )
                ->when(
                    $options->hasFormat(),
                    fn (Conversion $conversion) => $conversion->format($options->format),
                    fn (Conversion $conversion) => $conversion->keepOriginalImageFormat(),
                )
                ->when(
                    $options->shouldGenerateResponsiveImages(),
                    fn (Conversion $conversion) => $conversion->withResponsiveImages(),
                );
        }
    }

    /**
     * Get conversions.
     *
     * @return array<ConversionData>
     */
    public static function conversions(): array
    {
        return [
            ConversionOptions::make(
                key: 'thumb',
                width: 320,
                height: 320
            ),
            ConversionOptions::make(
                key: 'medium',
                width: 660,
                height: null
            ),
            ConversionOptions::make(
                key: 'webp',
                format: 'webp'
            ),
        ];
    }
}
