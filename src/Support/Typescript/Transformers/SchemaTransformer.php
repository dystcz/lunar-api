<?php

namespace Dystcz\LunarApi\Support\Typescript\Transformers;

use Config;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema as SchemaContract;
use Dystcz\LunarApi\Domain\JsonApi\V1\Server;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Eloquent\Fields\Attribute;
use LaravelJsonApi\Eloquent\Schema;
use phpDocumentor\Reflection\Type;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Attributes\Optional;
use Spatie\TypeScriptTransformer\Structures\MissingSymbolsCollection;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;
use Spatie\TypeScriptTransformer\Transformers\TransformsTypes;
use Spatie\TypeScriptTransformer\TypeProcessors\DtoCollectionTypeProcessor;
use Spatie\TypeScriptTransformer\TypeProcessors\ReplaceDefaultsTypeProcessor;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfig;

class SchemaTransformer implements Transformer
{
    use TransformsTypes;

    protected TypeScriptTransformerConfig $config;

    protected Server $server;

    public function __construct(TypeScriptTransformerConfig $config)
    {
        $this->config = $config;

        $this->server = $this->resolveServer();
    }

    /**
     * Resolve json:api server.
     */
    protected function resolveServer(): Server
    {
        $servers = Config::get('jsonapi.servers');
        $key = array_key_first($servers);

        return App::make($servers[$key], ['name' => $key]);
    }

    /**
     * Transform the class to a TypeScript type.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    public function transform(ReflectionClass $class, string $name): ?TransformedType
    {
        if (! $class->implementsInterface(SchemaContract::class) || ! $class->isSubclassOf(Schema::class)) {
            return null;
        }

        $missingSymbols = new MissingSymbolsCollection();

        $type = implode([
            $this->transformFields($class, $missingSymbols),
            $this->transformMethods($class, $missingSymbols),
            $this->transformExtra($class, $missingSymbols),
        ]);

        return TransformedType::create(
            $class,
            $name,
            '{'.PHP_EOL.$type.'}',
            $missingSymbols
        );
    }

    /**
     * Check if the class can be transformed.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    protected function canTransform(ReflectionClass $class): bool
    {
        if ($class->implementsInterface(SchemaContract::class) || $class->isSubclassOf(Schema::class)) {
            return true;
        }
    }

    /**
     * Transform the properties of the class.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    protected function transformFields(
        ReflectionClass $class,
        MissingSymbolsCollection $missingSymbols
    ): string {

        if (! $class->getName() === ProductSchema::class) {
            return '';
        }

        // dd($class->getAttributes());

        $isOptional = ! empty($class->getAttributes(Optional::class));

        return array_reduce(
            $this->resolveFields($class),
            function (string $carry, Field $field) use ($isOptional, $missingSymbols) {
                $class = new ReflectionClass($field);

                // If field is hidden, skip it
                if ($class->isSubclassOf(Attribute::class)) {
                    /** @var Attribute $field */
                    if ($field->isHidden(null)) {
                        return $carry;
                    }
                }

                $method = $class->getMethod('make');

                $transformed = $this->reflectionToTypeScript(
                    $method,
                    $missingSymbols,
                    ...$this->typeProcessors()
                );

                if ($transformed === null) {
                    return $carry;
                }

                $propertyName = $this->transformFieldName($class, $missingSymbols);

                return $isOptional
                    ? "{$carry}{$propertyName}?: {$transformed};".PHP_EOL
                    : "{$carry}{$propertyName}: {$transformed};".PHP_EOL;
            },
            ''
        );
    }

    /**
     * Transform the methods of the class.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    protected function transformMethods(
        ReflectionClass $class,
        MissingSymbolsCollection $missingSymbols
    ): string {
        return '';
    }

    /**
     * Transform extra properties of the class.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    protected function transformExtra(
        ReflectionClass $class,
        MissingSymbolsCollection $missingSymbols
    ): string {
        return '';
    }

    /**
     * Transform the property name.
     *
     * @param  ReflectionClass<Field>  $class
     */
    protected function transformFieldName(
        ReflectionClass $class,
        MissingSymbolsCollection $missingSymbols
    ): string {
        return $class->getName();
    }

    /**
     * Get the type processors.
     *
     * @return array<int,mixed>
     */
    protected function typeProcessors(): array
    {
        return [
            new ReplaceDefaultsTypeProcessor(
                $this->config->getDefaultTypeReplacements()
            ),
            // new DtoCollectionTypeProcessor(),
        ];
    }

    /**
     * Resolve the properties of the class.
     *
     * @param  ReflectionClass<SchemaContract|Schema>  $class
     */
    protected function resolveFields(ReflectionClass $class): array
    {
        // Create a new instance of the schema
        $schema = $class->newInstance($this->server);

        return array_values($schema->fields());
    }
}
