<?php

namespace Dystcz\LunarApi\Base\Manifests;

use Dystcz\LunarApi\Base\Contracts\Extendable;

abstract class Manifest
{
    /**
     * @var array<class-string<Extendable>, Extension>
     */
    public array $extensions = [];
}
