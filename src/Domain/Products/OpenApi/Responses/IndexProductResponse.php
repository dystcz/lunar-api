<?php

namespace Dystcz\LunarApi\Domain\Products\OpenApi\Responses;

use Dystcz\LunarApi\Domain\JsonApi\OpenApi\Schemas\JsonApiSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Lunar\Models\Product;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class IndexProductResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            JsonApiSchema::model(Product::class)
                ->collection()
                ->generate()
        );
    }
}
