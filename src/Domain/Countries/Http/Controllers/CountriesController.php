<?php

namespace Dystcz\LunarApi\Domain\Countries\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountryCollectionQuery;
use Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountrySchema;
use Illuminate\Support\Facades\Cache;
use LaravelJsonApi\Core\Responses\DataResponse;

class CountriesController extends Controller
{
    /**
     * Fetch zero to many JSON API resources.
     *
     * @param  PostSchema  $schema
     * @param  PostCollectionQuery  $request
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function index(CountrySchema $schema, CountryCollectionQuery $request): DataResponse
    {
        $models = Cache::rememberForever(
            'lunar-api.countries',
            fn () => $schema->repository()->queryAll()->withRequest($request)->get(),
        );

        return DataResponse::make($models);
    }
}
