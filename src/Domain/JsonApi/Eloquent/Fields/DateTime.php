<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use LaravelJsonApi\Eloquent\Fields\DateTime as LaravelJsonApiDateTime;
use Spatie\TypeScriptTransformer\Attributes\InlineTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[InlineTypeScriptType]
#[RecordTypeScriptType(keyType: 'string', valueType: 'string')]
class DateTime extends LaravelJsonApiDateTime {}
