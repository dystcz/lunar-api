<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class HasResource
{
    /**
     * Create a new attribute instance.
     *
     * @return void
     */
    public function __construct(public string $class) {}
}
