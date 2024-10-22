<?php

namespace Dystcz\LunarApi\Base\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ReplaceModel
{
    /**
     * Create a new attribute instance.
     *
     * @return void
     */
    public function __construct(public string $class) {}
}
