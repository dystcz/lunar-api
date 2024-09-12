<?php

namespace Dystcz\LunarApi\Domain\Media\Data;

use Illuminate\Contracts\Support\Arrayable;

class ConversionOptions implements Arrayable
{
    public function __construct(
        public string $key,
        public ?int $width = null,
        public ?int $height = null,
        public ?string $format = null,
        public array $collections = [],
        public bool $responsive = true,
        public bool $queue = true,
    ) {}

    /**
     * Static constructor.
     */
    public static function make(
        string $key,
        ?int $width = null,
        ?int $height = null,
        ?string $format = null,
        array $collections = [],
        bool $queue = true,
    ): self {
        return new self(
            key: $key,
            width: $width,
            height: $height,
            format: $format,
            collections: $collections,
            queue: $queue,
        );
    }

    /**
     * Determine if the conversion should change the dimensions of the file.
     */
    public function hasDimensions(): bool
    {
        return $this->width !== null && $this->height !== null;
    }

    /**
     * Determine if the conversion should change the format of the file.
     */
    public function hasFormat(): bool
    {
        return $this->format !== null;
    }

    /**
     * Determine if the conversion should be performed on specific collections.
     */
    public function hasCollections(): bool
    {
        return count($this->collections) > 0;
    }

    /**
     * Determine if the conversion should be queued.
     */
    public function shouldGenerateResponsiveImages(): bool
    {
        return $this->responsive;
    }

    /**
     * Determine if the conversion should be queued.
     */
    public function shouldQueue(): bool
    {
        return $this->queue;
    }

    /**
     * Convert the object to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'width' => $this->width,
            'height' => $this->height,
            'format' => $this->format,
            'collections' => $this->collections,
            'queue' => $this->queue,
        ];
    }
}
