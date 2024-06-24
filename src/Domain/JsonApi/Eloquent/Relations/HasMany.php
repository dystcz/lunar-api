<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Relations;

use Dystcz\LunarApi\Support\Typescript\Types\RelationType;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany as LaravelJsonApiHasMany;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;

#[RecordTypeScriptType(keyType: 'string', valueType: RelationType::class)]
class HasMany extends LaravelJsonApiHasMany {}
