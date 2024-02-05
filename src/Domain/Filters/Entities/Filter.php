<?php

namespace Dystcz\LunarApi\Domain\Filters\Entities;

use Illuminate\Contracts\Support\Arrayable;

class Filter implements Arrayable
{
    /**
     * @param  array<int,FilterOption>  $options
     * @param  array<string,mixed>  $meta
     */
    public function __construct(
        public string $handle,
        public string $data_type,
        public string $name,
        public int $position,
        public ?array $options = null,
        public array $meta = [],
    ) {
    }

    /**
     * Get the handle of the filter.
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * Get the data type of the filter.
     */
    public function getDataType(): string
    {
        return $this->data_type;
    }

    /**
     * Get the name of the filter.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the position of the filter.
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Get the options of the filter.
     *
     * @return array<int,FilterOption>
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Get the meta of the filter.
     *
     * @return array<string,mixed>
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'handle' => $this->getHandle(),
            'data_type' => $this->getDataType(),
            'name' => $this->getName(),
            'position' => $this->getPosition(),
            'options' => $this->getOptions(),
            'meta' => $this->getMeta(),
        ];
    }
}
