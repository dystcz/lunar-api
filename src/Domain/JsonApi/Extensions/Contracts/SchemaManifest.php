<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts;

interface SchemaManifest extends Manifest
{
    /** {@inheritDoc} */
    public static function for(string $class): Extension;
}
