<?php

use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Tests\Stubs\Enums\TestEnum;
use Dystcz\LunarApi\Tests\Stubs\Support\Typescript\TestTypeScriptTransformerConfig;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithConsoleEvents;
use Illuminate\Support\Str;

uses(TestCase::class, RefreshDatabase::class, WithConsoleEvents::class);

it('can generate typescript types', function () {
    /** @var TestCase $this */
    $this->app->bind(
        'lunar-api.typescript-transformer-config',
        fn () => TestTypeScriptTransformerConfig::create(),
    );

    $enumStubFileName = 'TestEnum.php';
    $enumStubPath = "/tests/Stubs/Enums/{$enumStubFileName}";
    $enum = file_get_contents(LunarApi::getPackageRoot().$enumStubPath);
    Storage::put("stubs/{$enumStubFileName}", $enum);
    $outputPath = '/types/lunar-api-resources.d.ts';
    $resourceOutputPath = resource_path($outputPath);
    $inputPath = Storage::path('stubs/TestEnum.php');

    $this->artisan('lunar-api:generate-typescript-types', [
        '--path' => $inputPath,
        '--output' => $outputPath,
    ])
        ->expectsTable(
            ['PHP class', 'TypeScript entity'],
            [TestEnum::class => [
                TestEnum::class,
                Str::replace('\\', '.', TestEnum::class),
            ]],
        )
        ->expectsOutput("Generated 1 types in \"{$resourceOutputPath}\".")
        ->assertExitCode(0);
})->group('support', 'typescript');
