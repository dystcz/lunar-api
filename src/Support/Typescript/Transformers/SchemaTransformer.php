<?php

namespace Dystcz\LunarApi\Support\Typescript\Transformers;

use Dystcz\LunarApi\Base\Facades\SchemaManifestFacade;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema as SchemaContract;
use Dystcz\LunarApi\Domain\JsonApi\V1\Server;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Relation as RelationContract;
use LaravelJsonApi\Eloquent\Fields\Attribute;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Schema;
use phpDocumentor\Reflection\Type;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Structures\MissingSymbolsCollection;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;
use Spatie\TypeScriptTransformer\Transformers\TransformsTypes;
use Spatie\TypeScriptTransformer\Types\TypeScriptType;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfig;

class SchemaTransformer implements Transformer
{
    use TransformsTypes;

    protected TypeScriptTransformerConfig $config;

    protected Server $server;

    protected array $schemas;

    public function __construct(TypeScriptTransformerConfig $config)
    {
        $this->config = $config;

        $this->server = $this->resolveServer();

        $this->schemas = SchemaManifestFacade::getServerSchemas();
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
        if (! $this->canTransform($class)) {
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

        return false;
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
        return array_reduce(
            $this->resolveFields($class),
            function (string $carry, Field $field) use ($missingSymbols) {
                $class = new ReflectionClass($field);

                // If field is hidden, skip it
                if ($class->isSubclassOf(Attribute::class)) {
                    /** @var Attribute $field */
                    if ($field->isHidden(null)) {
                        return $carry;
                    }
                }

                $method = $class->getMethod('make');

                $type = $this->reflectionToTypeScript(
                    $method,
                    $missingSymbols,
                );

                // // Fields tramsformed to String
                // if (in_array($fieldClass->getName(), [Str::class, ID::class])) {
                //     $type = TypeScriptType::create('string');
                // }
                //
                // if ($fieldClass->implementsInterface(RelationContract::class)) {
                //     /** @var RelationContract $field */
                //     $relation = $field->inverse() ?? $field->name();
                //
                //     $relatedSchema = Arr::first($this->schemas, fn ($schena) => $schena::type() === $relation);
                //
                //     if ($relatedSchema) {
                //         $relatedSchemaClass = new ReflectionClass($relatedSchema);
                //         $type = TypeScriptType::create("{$relatedSchemaClass->getShortName()}[]");
                //     }
                // }

                $isOptional = true;
                $assignOperator = $isOptional ? '?:' : ':';

                return "{$carry}{$field->name()}{$assignOperator} {$type};".PHP_EOL;
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
