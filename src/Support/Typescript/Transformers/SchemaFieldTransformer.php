<?php

namespace Dystcz\LunarApi\Support\Typescript\Transformers;

use Dystcz\LunarApi\Support\Typescript\TypeProcessors\SchemaFieldTypeProcessor;
use LaravelJsonApi\Contracts\Schema\Field as FieldContract;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Structures\MissingSymbolsCollection;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;
use Spatie\TypeScriptTransformer\Transformers\TransformsTypes;
use Spatie\TypeScriptTransformer\TypeReflectors\ClassTypeReflector;

class SchemaFieldTransformer implements Transformer
{
    use TransformsTypes;

    /**
     * Transform the class to a TypeScript type.
     *
     * @param  ReflectionClass<FieldContract>  $class
     */
    public function transform(ReflectionClass $class, string $name): ?TransformedType
    {
        $reflector = ClassTypeReflector::create($class);

        $type = $reflector->getType();
        $inline = $reflector->isInline();

        $missingSymbols = new MissingSymbolsCollection();

        $class = $class
            ->getMethod('make')
            ->getDeclaringClass();

        $transformed = $this->typeToTypeScript(
            type: $type,
            missingSymbolsCollection: $missingSymbols,
            currentClass: $class->getName(),
        );

        $type = TransformedType::create(
            class: $class,
            name: $name,
            transformed: $transformed,
            missingSymbols: $missingSymbols
        );

        return $type;
    }

    /**
     * Get the type processors.
     *
     * @return array<int,mixed>
     */
    protected function typeProcessors(): array
    {
        return [
            // new SchemaFieldTypeProcessor,
        ];
    }
}
