<?php

namespace Dystcz\LunarApi\Support\Typescript\Collectors;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema as SchemaContract;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Support\Typescript\Transformers\SchemaTransformer;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Collectors\DefaultCollector;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\TypeReflectors\ClassTypeReflector;

class SchemaCollector extends DefaultCollector
{
    /**
     * Get the transformed type for the given class.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    public function getTransformedType(ReflectionClass $class): ?TransformedType
    {
        if (! $this->shouldCollect($class)) {
            return null;
        }

        $reflector = ClassTypeReflector::create($class);

        $transformedType = $reflector->getType()
            ? $this->resolveAlreadyTransformedType($reflector)
            : $this->resolveTypeViaTransformer($reflector);

        if ($reflector->isInline()) {
            $transformedType->name = null;
            $transformedType->isInline = true;
        }

        return $transformedType;
    }

    /**
     * Determine if the class should be collected.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    protected function shouldCollect(ReflectionClass $class): bool
    {
        if ($class->getName() !== ProductSchema::class) {
            return false;
        }

        $transformers = array_map('get_class', $this->config->getTransformers());

        $hasSchemaTransformer = \count(
            array_filter($transformers, function (string $transformer) {
                if ($transformer === SchemaTransformer::class) {
                    return true;
                }

                return is_subclass_of($transformer, SchemaTransformer::class);
            }),
        ) > 0;

        if (! $hasSchemaTransformer) {
            return false;
        }

        if (! $class->implementsInterface(SchemaContract::class)) {
            return false;
        }

        return true;
    }
}
