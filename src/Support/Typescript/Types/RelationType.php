<?php

namespace Dystcz\LunarApi\Support\Typescript\Types;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Array_;
use Spatie\TypeScriptTransformer\Types\RecordType;
use Spatie\TypeScriptTransformer\Types\StructType;

/** @psalm-immutable */
class RelationType extends RecordType
{
    private Type $keyType;

    private Type $valueType;

    public function __construct(string $keyType, string|array $valueType, ?bool $array = false)
    {
        $this->keyType = (new TypeResolver())->resolve($keyType);

        if ($array) {
            $this->valueType = new Array_((new TypeResolver())->resolve($valueType));
        } else {
            $this->valueType = is_array($valueType)
                ? StructType::fromArray($valueType)
                : (new TypeResolver())->resolve($valueType);
        }
    }

    public function __toString(): string
    {
        return 'record';
    }

    public function getKeyType(): Type
    {
        return $this->keyType;
    }

    public function getValueType(): Type
    {
        return $this->valueType;
    }
}
