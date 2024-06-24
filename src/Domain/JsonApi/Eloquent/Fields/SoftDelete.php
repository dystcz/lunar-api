<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use LaravelJsonApi\Eloquent\Fields\SoftDelete as LaravelJsonApiSoftDelete;
use Spatie\TypeScriptTransformer\Attributes\RecordTypeScriptType;

#[RecordTypeScriptType(keyType: 'string', valueType: 'string')]
class SoftDelete extends LaravelJsonApiSoftDelete {}
