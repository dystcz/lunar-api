<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use LaravelJsonApi\Eloquent\Fields\ArrayHash as LaravelJsonApiArrayHash;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;

#[RecordTypeScriptType(keyType: 'string', valueType: 'Array[]')]
class ArrayHash extends LaravelJsonApiArrayHash
{
}
