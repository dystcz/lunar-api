<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts;

interface ResourceManifest extends Manifest
{
    /** {@inheritDoc} */
    public static function for(string $class): Extension;
}
