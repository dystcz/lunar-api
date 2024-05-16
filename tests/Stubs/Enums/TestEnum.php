<?php

namespace Dystcz\LunarApi\Tests\Stubs\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum TestEnum: string
{
    case A = 'a';
    case B = 'b';
    case C = 'c';
}
