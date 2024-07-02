<?php

namespace Dystcz\LunarApi\Support\Typescript\TypeProcessors;

use LaravelJsonApi\Contracts\Schema\Field as FieldContract;
use LaravelJsonApi\Contracts\Schema\Relation as RelationContract;
use phpDocumentor\Reflection\Type;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Spatie\TypeScriptTransformer\Structures\MissingSymbolsCollection;
use Spatie\TypeScriptTransformer\TypeProcessors\ProcessesTypes;
use Spatie\TypeScriptTransformer\TypeProcessors\TypeProcessor;
use Spatie\TypeScriptTransformer\TypeReflectors\ClassTypeReflector;

class SchemaFieldTypeProcessor implements TypeProcessor
{
    use ProcessesTypes;

    public function process(
        Type $type,
        ReflectionProperty|ReflectionParameter|ReflectionMethod $reflection,
        MissingSymbolsCollection $missingSymbolsCollection
    ): ?Type {
        return $this->walk($type, function (Type $type) use ($reflection) {
            $reflector = ClassTypeReflector::create(
                $this->guessTransformableClass(
                    $reflection->getDeclaringClass(),
                ),
            );

            $reflectedType = $reflector->getType();

            if (! $reflectedType) {
                return $type;
            }

            return $type;
        });
    }

    /**
     * Guess the transformable class reflection.
     *
     * @param  ReflectionClass<FieldContract>  $class
     */
    protected function guessTransformableClass(ReflectionClass $class): ReflectionClass
    {
        $shortName = $class->getShortName();

        $namespace = 'Dystcz\\LunarApi\\Domain\\JsonApi\\Eloquent\\Fields';

        if ($class->implementsInterface(RelationContract::class)) {
            $namespace .= '\\Relations';
        }

        if (class_exists("{$namespace}\\{$shortName}")) {
            $class = new ReflectionClass("{$namespace}\\{$shortName}");
        }

        return $class;
    }
}
