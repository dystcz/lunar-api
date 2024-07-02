<?php

namespace Dystcz\LunarApi\Support\Typescript\Collectors;

use Dystcz\LunarApi\Support\Typescript\Transformers\SchemaFieldTransformer;
use LaravelJsonApi\Contracts\Schema\Field as FieldContract;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Collectors\DefaultCollector;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\TypeReflectors\ClassTypeReflector;

class SchemaFieldCollector extends DefaultCollector
{
    /**
     * Get the transformed type for the given class.
     *
     * @param  ReflectionClass<FieldContract>  $class
     */
    public function getTransformedType(ReflectionClass $class): ?TransformedType
    {
        if (! $this->shouldCollect($class)) {
            return null;
        }

        $reflector = ClassTypeReflector::create($class);

        $transformedType = $this->resolveTypeViaTransformer($reflector);

        if ($reflector->isInline()) {
            $transformedType->name = null;
            $transformedType->isInline = true;
        }

        return $transformedType;
    }

    /**
     * Determine if the class should be collected.
     *
     * @param  ReflectionClass<FieldContract>  $class
     */
    protected function shouldCollect(ReflectionClass $class): bool
    {
        $transformers = array_map('get_class', $this->config->getTransformers());

        $hasTransformer = count(
            array_filter($transformers, function (string $transformer) {
                if ($transformer === SchemaFieldTransformer::class) {
                    return true;
                }

                return is_subclass_of($transformer, SchemaFieldTransformer::class);
            }),
        ) > 0;

        if (! $hasTransformer) {
            return false;
        }

        if (! $class->implementsInterface(FieldContract::class)) {
            return false;
        }

        return true;
    }
}
