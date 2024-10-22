<?php

namespace Dystcz\LunarApi\Support\Config\Collections;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\JsonApi\Queries\CollectionQuery;
use Dystcz\LunarApi\Domain\JsonApi\Queries\Query;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup;
use Dystcz\LunarApi\Support\Config\Data\DomainConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use LaravelJsonApi\Contracts\Schema\Schema as SchemaContract;
use LaravelJsonApi\Core\Schema\Schema as BaseSchema;
use ReflectionAttribute;
use ReflectionClass;
use Spatie\StructureDiscoverer\Data\DiscoveredStructure;
use Spatie\StructureDiscoverer\Discover;
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;

class DomainConfigCollection extends Collection
{
    /**
     * Create domain config collection.
     */
    public static function make($items = []): self
    {
        if (! empty($items)) {
            return new static($items);
        }

        return self::fromConfig('lunar-api.domains');
    }

    /**
     * Create domain config collection from a given config file.
     */
    public static function discover(?string $root = null): self
    {
        $root = $root ?? LunarApi::getRoot();

        $schemas = Discover::in($root)
            ->classes()
            ->any(
                ConditionBuilder::create()
                    ->implementing(SchemaContract::class)
                    ->custom(fn (DiscoveredStructure $structure) => ! $structure->isAbstract),

                ConditionBuilder::create()
                    ->custom(fn (DiscoveredStructure $structure) => in_array($structure->extends, [BaseSchema::class]))
            )
            ->get();

        $domains = Collection::make($schemas)
            ->mapWithKeys(function (string $schema) use ($root) {
                $domain = (string) Str::of($schema)->after('Domain')->betweenFirst('\\', '\\');
                $domainRoot = "{$root}/Domain/{$domain}";

                $model = Arr::first(
                    Discover::in($domainRoot)
                        ->classes()
                        ->custom(fn (DiscoveredStructure $structure) => Str::contains("{$structure->namespace}/{$structure->name}", "Models/{$structure->name}"))
                        ->custom(fn (DiscoveredStructure $structure) => ! in_array(Pivot::class, $structure->extendsChain))
                        ->get()
                );

                if ($model) {
                    $modelReflection = new ReflectionClass($model);
                    /** @var ReflectionAttribute|null $replacesModelAttribute */
                    $replacesModelAttribute = Arr::first(
                        $modelReflection->getAttributes(),
                        fn (ReflectionAttribute $attribute) => $attribute->getName() === ReplaceModel::class,
                    );
                    $modelContract = Arr::first($replacesModelAttribute->getArguments());
                }

                $policy = Arr::first(
                    Discover::in($domainRoot)
                        ->classes()
                        ->custom(fn (DiscoveredStructure $structure) => Str::contains($structure->namespace, 'Policies'))
                        ->get()
                );

                $resource = Arr::first(
                    Discover::in($domainRoot)
                        ->classes()
                        ->extending(JsonApiResource::class)
                        ->get()
                );

                $query = Arr::first(
                    Discover::in($domainRoot)
                        ->classes()
                        ->extending(Query::class)
                        ->get()
                );

                $collectionQuery = Arr::first(
                    Discover::in($domainRoot)
                        ->classes()
                        ->extending(CollectionQuery::class)
                        ->get()
                );

                $routeGroup = Arr::first(
                    Discover::in($domainRoot)
                        ->classes()
                        ->implementing(RouteGroup::class)
                        ->custom(fn (DiscoveredStructure $structure) => Str::contains($structure->namespace, 'Domain'))
                        ->get()
                );

                return [
                    $schema::type() => new DomainConfig(
                        domain: $schema::type(),
                        schema: $schema,
                        model: $model,
                        model_contract: $modelContract ?? null,
                        policy: $policy,
                        resource: $resource,
                        query: $query,
                        collection_query: $collectionQuery,
                        routes: $routeGroup,
                    ),
                ];
            });

        return new static($domains);
    }

    /**
     * Create domain config collection from a given config file.
     */
    public static function fromConfig(string $configKey): self
    {
        $items = array_map(
            fn (string $domain, array $config) => new DomainConfig($domain, ...$config),
            array_keys(Config::get($configKey, [])),
            array_values(Config::get($configKey, []))
        );

        return new static($items);
    }

    /**
     * Get schemas from domain config.
     */
    public function getSchemas(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->hasSchema()) {
                return [];
            }

            return [$domain->schema::type() => $domain->schema];
        });
    }

    /**
     * Get schemas from domain config.
     */
    public function getSchemaByType(string $type): string
    {
        return $this->firstWhere(
            fn (DomainConfig $domain) => $domain->schema::type() === $type,
        );
    }

    /**
     * Get routes from domain config.
     */
    public function getRoutes(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->hasRoutes()) {
                return [];
            }

            return [$domain->schema::type() => $domain->routes];
        });
    }

    /**
     * Get models for Lunar model manifest.
     */
    public function getModelsForModelManifest(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->swapsModel()) {
                return [];
            }

            return [$domain->model_contract => $domain->model];
        });
    }

    /**
     * Get policies from domain config.
     */
    public function getPolicies(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->hasPolicy()) {
                return [];
            }

            return [$domain->model => $domain->policy];
        });
    }
}
