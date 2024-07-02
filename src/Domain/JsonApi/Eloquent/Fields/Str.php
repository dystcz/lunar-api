<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use LaravelJsonApi\Eloquent\Fields\Str as LaravelJsonApiStr;
use Spatie\TypeScriptTransformer\Attributes\InlineTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[InlineTypeScriptType]
#[LiteralTypeScriptType('string')]
class Str extends LaravelJsonApiStr {}
