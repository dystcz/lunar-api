<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Authorizers;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use LaravelJsonApi\Contracts\Auth\Authorizer as AuthorizerContract;
use LaravelJsonApi\Core\Auth\Authorizer as BaseAuthorizer;
use LaravelJsonApi\Core\JsonApiService;
use LaravelJsonApi\Core\Support\Str;
use ReflectionClass;

class Authorizer extends BaseAuthorizer implements AuthorizerContract
{
    protected Gate $gate;

    protected JsonApiService $service;

    protected ReflectionClass $reflection;

    /**
     * AnonymousAuthorizer constructor.
     */
    public function __construct(Gate $gate, JsonApiService $service)
    {
        $this->gate = $gate;

        $this->service = $service;

        parent::__construct($gate, $service);
    }

    /**
     * Authorize the show-related controller action.
     */
    public function showRelated(Request $request, object $model, string $fieldName): bool
    {
        $ability = 'view'.Str::classify($fieldName);

        if (! $this->mustAuthorize()) {
            return true;
        }

        if (method_exists($this->gate->getPolicyFor($model), $ability)) {
            return $this->gate->check($ability, $model);
        }

        return $this->authorizeFromSchema('showRelated', func_get_args());
    }

    /**
     * Authorize the show-relationship controller action.
     */
    public function showRelationship(Request $request, object $model, string $fieldName): bool
    {
        $ability = 'view'.Str::classify($fieldName);

        if (! $this->mustAuthorize()) {
            return true;
        }

        if (method_exists($this->gate->getPolicyFor($model), $ability)) {
            return $this->gate->check($ability, $model);
        }

        return $this->authorizeFromSchema('showRelationship', func_get_args());
    }

    /**
     * Authorize from schema.
     */
    protected function authorizeFromSchema(string $method, array $args): bool
    {
        $fieldName = Arr::first($args, fn ($arg) => is_string($arg));

        if (! method_exists($this->schema(), $method)) {
            return false;
        }

        $allowed = $this->schema()->{$method}();

        if (in_array($fieldName, $allowed)) {
            return true;
        }

        return false;
    }

    /**
     * Should default resource authorization be run?
     *
     * For authorization to be triggered, authorization must
     * be enabled for both the server AND the resource schema.
     */
    private function mustAuthorize(): bool
    {
        if ($this->service->server()->authorizable()) {
            return $this->schema()->authorizable();
        }

        return false;
    }

    /**
     * Get schema.
     */
    protected function schema(): Schema
    {
        return $this->service->route()->schema();
    }
}
