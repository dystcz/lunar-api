<?php

namespace Dystcz\LunarApi\Domain\Products\OpenApi\Parameters;

use Illuminate\Support\Facades\App;
use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductJsonApiBuilder;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class IndexProductParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            ...App::get(ProductJsonApiBuilder::class)->parametersSchema(),
        ];
    }
}
