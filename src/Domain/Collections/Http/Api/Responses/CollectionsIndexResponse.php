<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Api\Responses;

use Dystcz\LunarApi\Domain\Collections\Http\Api\Schemas\CollectionSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class CollectionsIndexResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            MediaType::json()->schema(CollectionSchema::ref())
        );
    }
}
