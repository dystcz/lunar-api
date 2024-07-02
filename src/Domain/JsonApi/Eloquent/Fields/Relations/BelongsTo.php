<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\Relations;

use Dystcz\LunarApi\Support\Typescript\Types\RelationType;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo as LaravelJsonApiBelongsTo;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;

#[RecordTypeScriptType(keyType: 'string', valueType: RelationType::class)]
class BelongsTo extends LaravelJsonApiBelongsTo {}
