<?php

namespace Dystcz\LunarApi\Support\Config\Actions;

use Dystcz\LunarApi\Support\Actions\Action;
use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;

class RegisterRoutesFromConfig extends Action
{
    public function handle(): void
    {
        $domains = DomainConfigCollection::make()
            ->getRoutes()
            ->each(
                fn (string $routeGroup, string $schemaType) => $routeGroup::make($schemaType)->routes(),
            );
    }
}
