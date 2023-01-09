<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions;

interface Manifest
{
    public static function for(string $class): Extension;
}
