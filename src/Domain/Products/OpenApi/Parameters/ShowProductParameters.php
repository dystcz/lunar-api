<?php

namespace Dystcz\LunarApi\Domain\Products\OpenApi\Parameters;

use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductJsonApiBuilder;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class ShowProductParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            Parameter::path()
                ->name('slug')
                ->description('Slug of the product')
                ->example('my-product')
                ->required()
                ->schema(Schema::string()),

            ...app(ProductJsonApiBuilder::class)->parametersSchema(),
        ];
    }
}
