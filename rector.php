<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Rector\ClassMethod\AddGenericReturnTypeToRelationsRector;
use RectorLaravel\Rector\ClassMethod\MigrateToSimplifiedAttributeRector;
use RectorLaravel\Rector\FuncCall\FactoryFuncCallToStaticCallRector;
use RectorLaravel\Rector\FuncCall\HelperFuncCallToFacadeClassRector;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tinkerwell',
    ]);

    $rectorConfig->importNames();

    // register a single rule

    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(AddGenericReturnTypeToRelationsRector::class);
    $rectorConfig->rule(FactoryFuncCallToStaticCallRector::class);
    $rectorConfig->rule(HelperFuncCallToFacadeClassRector::class);
    $rectorConfig->rule(MigrateToSimplifiedAttributeRector::class);
    $rectorConfig->rule(SeparateMultiUseImportsRector::class);

    $rectorConfig->sets([
        SetList::PHP_81,
        LaravelSetList::LARAVEL_100,
    ]);
};
