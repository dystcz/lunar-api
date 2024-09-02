<?php

namespace Dystcz\LunarApi\Domain\Media\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\MediaConversion;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Media\Contracts\MediaConversion as MediaConversionContract;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var Media $model */
        $model = $this->resource;

        return parent::attributes($request);
    }

    /**
     * Get all resource's attributes.
     *
     * @param  Request|null  $request
     */
    protected function allAttributes($request): iterable
    {
        if (! $request->has('media_conversions')) {
            return parent::allAttributes($request);
        }

        /** @var Media $model */
        $model = $this->resource;

        $conversions = array_filter(
            explode(',', $request->get('media_conversions', '')),
            fn ($conversion) => $model->hasGeneratedConversion($conversion),
        );

        return [
            ...parent::allAttributes($request),
            ...$this->conversions($conversions),
        ];
    }

    /**
     * Map media conversions to fields.
     *
     * @param  array<int, MediaConversionContract>  $conversions
     */
    protected function conversions(array $conversions): array
    {
        return array_reduce($conversions, function (array $carry, string $conversion) {
            array_push($carry, MediaConversion::make($conversion));

            return $carry;
        }, []);
    }
}
