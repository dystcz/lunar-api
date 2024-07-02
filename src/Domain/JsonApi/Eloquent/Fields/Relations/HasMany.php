<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\Relations;

use Dystcz\LunarApi\Support\Typescript\Types\RelationType;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany as LaravelJsonApiHasMany;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[RecordTypeScriptType(keyType: 'string', valueType: RelationType::class)]
class HasMany extends LaravelJsonApiHasMany {}
