<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;

abstract class Manifest
{
    /**
     * @var array<class-string<Extendable>, Extension>
     */
    protected array $extensions = [];
}
