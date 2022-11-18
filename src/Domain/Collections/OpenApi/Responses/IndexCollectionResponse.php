<?php

namespace Dystcz\LunarApi\Domain\Collections\OpenApi\Responses;

use Dystcz\LunarApi\Domain\JsonApi\OpenApi\Schemas\JsonApiSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Lunar\Models\Collection;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class CollectionsIndexResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            JsonApiSchema::model(Collection::class)
                ->collection()
                ->generate()
        );
    }
}
