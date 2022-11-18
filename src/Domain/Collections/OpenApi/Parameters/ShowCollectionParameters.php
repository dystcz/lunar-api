<?php

namespace Dystcz\LunarApi\Domain\Collections\OpenApi\Parameters;

use Dystcz\LunarApi\Domain\JsonApi\Builders\CollectionJsonApiBuilder;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class ShowCollectionParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            Parameter::path()
                ->name('slug')
                ->description('Slug of the collection')
                ->example('my-collection')
                ->required()
                ->schema(Schema::string()),

            ...app(CollectionJsonApiBuilder::class)->parametersSchema(),
        ];
    }
}
