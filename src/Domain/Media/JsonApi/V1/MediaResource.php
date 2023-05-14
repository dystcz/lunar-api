<?php

namespace Dystcz\LunarApi\Domain\Media\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\MediaConversion;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Media\Data\ConversionOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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

        return [
            ...parent::allAttributes($request),
            ...$this->conversions(explode(',', $request->get('media_conversions'))),
        ];
    }

    /**
     * Map media conversions to fields.
     */
    protected function conversions(array $keys): array
    {
        if (empty($keys)) {
            return [];
        }

        if (! $conversions = Config::get('lunar-api.domains.media.conversions', false)) {
            return [];
        }

        /** @var MediaConversionContract $conversions */
        $keys = array_map(
            fn (ConversionOptions $options) => $options->key,
            array_filter($conversions::conversions(), fn (ConversionOptions $options) => in_array($options->key, $keys)),
        );

        return array_reduce($keys, function (array $carry, string $conversion) {
            array_push($carry, MediaConversion::make($conversion));

            return $carry;
        }, []);
    }
}
