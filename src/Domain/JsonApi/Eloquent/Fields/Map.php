<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use LaravelJsonApi\Eloquent\Fields\Map as LaravelJsonApiMap;
use Spatie\TypeScriptTransformer\Attributes\InlineTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[InlineTypeScriptType]
#[RecordTypeScriptType(keyType: 'string', valueType: 'any')]
class Map extends LaravelJsonApiMap {}
