<?php

namespace Dystcz\LunarApi\Domain\Media\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function thumbnail(): self
    {
        return $this->state([
            'custom_properties' => [
                'primary' => true,
            ],
        ]);
    }

    public function definition(): array
    {
        return [
            'name' => 'image',
            'file_name' => 'image.jpg',
            'collection_name' => 'images',
            'disk' => 'public',
            'size' => '69',
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ];
    }
}
