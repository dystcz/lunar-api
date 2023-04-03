<?php

namespace Dystcz\LunarApi\Domain\Collections\OpenApi\Parameters;

use Dystcz\LunarApi\Domain\JsonApi\Builders\CollectionJsonApiBuilder;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Facades\App;
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
                ->example('mechanical-keyboards')
                ->required()
                ->schema(Schema::string()),

            ...App::get(CollectionJsonApiBuilder::class)->parametersSchema(),
        ];
    }
}
