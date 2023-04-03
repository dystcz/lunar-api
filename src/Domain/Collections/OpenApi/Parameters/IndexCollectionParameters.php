<?php

namespace Dystcz\LunarApi\Domain\Collections\OpenApi\Parameters;

use Illuminate\Support\Facades\App;
use Dystcz\LunarApi\Domain\JsonApi\Builders\CollectionJsonApiBuilder;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class IndexCollectionParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            ...App::get(CollectionJsonApiBuilder::class)->parametersSchema(),
        ];
    }
}
