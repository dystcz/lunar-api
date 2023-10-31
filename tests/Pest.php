<?php

use Dystcz\LunarApi\Support\Models\Actions\GetModelKey;
use Dystcz\LunarApi\Tests\Stubs\Lunar\TestUrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Base\BaseModel;
use Lunar\Database\Factories\LanguageFactory;

/**
 * Get server url.
 */
function serverUrl(string $path = null, bool $full = false): string
{
    $path = implode('/', [Config::get('lunar-api.general.route_prefix'), 'v1', ltrim($path, '/')]);

    if ($full) {
        return "http://localhost/{$path}";
    }

    return $path;
}

/**
 * Decode hashed id.
 */
function decodeHashedId(BaseModel $model, mixed $id): mixed
{
    /** @var \Vinkla\Hashids\Facades\Hashids $hashids */
    $hashids = App::get('hashids');

    return $hashids->connection((new GetModelKey)($model))->decode($id);
}

/**
 * Map models to response data.
 */
function mapModelsToResponseData(string $type, Collection $models): Collection
{
    return $models->map(fn ($model) => [
        'type' => $type,
        'id' => $model->getRouteKey(),
    ]);
}

/**
 * Turn on url generator.
 */
function generateUrls(): void
{
    LanguageFactory::new()->create();

    /** @var TestCase $this */
    Config::set('lunar.urls.generator', \Lunar\Generators\UrlGenerator::class);
}

/**
 * Turn off url generator.
 */
function dontGenerateUrls(): void
{
    /** @var TestCase $this */
    Config::set('lunar.urls.generator', TestUrlGenerator::class);
}
