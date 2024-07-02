<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\Relations;

use Dystcz\LunarApi\Support\Typescript\Types\RelationType;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphToMany as LaravelJsonApiMorphToMany;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;

#[RecordTypeScriptType(keyType: 'string', valueType: RelationType::class)]
class MorphToMany extends LaravelJsonApiMorphToMany {}
