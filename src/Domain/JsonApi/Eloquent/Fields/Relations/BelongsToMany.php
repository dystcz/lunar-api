<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\Relations;

use Dystcz\LunarApi\Support\Typescript\Types\RelationType;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany as LaravelJsonApiBelongsToMany;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;

#[RecordTypeScriptType(keyType: 'string', valueType: RelationType::class)]
class BelongsToMany extends LaravelJsonApiBelongsToMany {}
