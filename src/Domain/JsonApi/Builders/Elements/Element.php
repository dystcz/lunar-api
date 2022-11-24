<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Elements;

use Dystcz\LunarApi\Domain\JsonApi\Builders\JsonApiBuilder;

abstract class Element
{
    public function __construct(
        protected string $name,
        protected string $builder,
    ) {
    }

    public static function make(string $name, string $builder): static
    {
        return new static(name: $name, builder: $builder);
    }

    public function setBuilder(string $builder): static
    {
        $this->builder = $builder;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBuilderClass(): string
    {
        return $this->builder;
    }

    public function getBuilder(): JsonApiBuilder
    {
        return new $this->builder;
    }
}
