<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\Relations;

use Dystcz\LunarApi\Support\Typescript\Types\RelationType;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOneThrough as LaravelJsonApiHasOneThrough;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;

#[RecordTypeScriptType(keyType: 'string', valueType: RelationType::class)]
class HasOneThrough extends LaravelJsonApiHasOneThrough {}
