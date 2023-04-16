<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->server = App::make(ServerMock::class, ['name' => 'v1']);
});

test('schema eager loading can be extended', function () {
    /** @var TestCase $this */
    $productSchemaInstance = $this->server->schemas()->schemaFor('products');

    expect($productSchemaInstance)
        ->with()
        ->not
        ->toContain('else')
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)->with([
        'something',
        'else',
        'else',
    ]);

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->with()
        ->toContain(...['something', 'else']);

    expect($productSchemaInstance)
        ->with()
        ->toBe([
            'something',
            'else',
        ]);
});

test('schema fields can be extended', function () {
    /** @var TestCase $this */
    $productSchemaInstance = $this->server->schemas()->schemaFor('products');

    $field = Str::make('my_product_name');

    expect($productSchemaInstance)
        ->filters()
        ->not
        ->toContain($field)
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->fields([
            $field,
        ]);

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->fields()
        ->toContain($field)
        ->toHaveCount(1);

    expect($productSchemaInstance)
        ->fields()
        ->toContain($field)
        ->toHaveCount(2);
});

test('schema filters can be extended', function () {
    /** @var TestCase $this */
    $productSchemaInstance = $this->server->schemas()->schemaFor('products');

    $filter = Where::make('price');

    expect($productSchemaInstance)
        ->filters()
        ->not
        ->toContain($filter)
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->filters([
            $filter,
        ]);

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->filters()
        ->toContain($filter)
        ->toHaveCount(1);

    expect($productSchemaInstance)
        ->filters()
        ->toContain($filter)
        ->toHaveCount(2);
});

test('schema sortables can be extended', function () {
    /** @var TestCase $this */
    $productSchemaInstance = $this->server->schemas()->schemaFor('products');

    $sortables = ['nazdar', 'cau'];

    expect($productSchemaInstance)
        ->sortables()
        ->not
        ->toContain('nazdar', 'cau')
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->sortables(
            $sortables,
        );

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->sortables()
        ->toContain('nazdar', 'cau')
        ->toHaveCount(2);

    expect($productSchemaInstance)
        ->sortables()
        ->toContain('ahoj', 'nazdar', 'cau')
        ->toHaveCount(3);
});

test('schema related gate ability can be extended', function () {
    /** @var TestCase $this */
    $productSchemaInstance = $this->server->schemas()->schemaFor('products');

    $related = ['two', 'three', 'four', 'one'];

    expect($productSchemaInstance)
        ->showRelated()
        ->not
        ->toContain('two', 'three', 'four')
        ->toHaveCount(1)
        ->toContain('one');

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->showRelated(
            $related,
        );

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->showRelated()
        ->toContain('two', 'three', 'four', 'one')
        ->toHaveCount(4);

    expect($productSchemaInstance)
        ->showRelated()
        ->toContain('two', 'three', 'four', 'one')
        ->toHaveCount(4);
});

test('schema relationships gate ability can be extended', function () {
    /** @var TestCase $this */
    $productSchemaInstance = $this->server->schemas()->schemaFor('products');

    $relationships = ['pear', 'peach'];

    expect($productSchemaInstance)
        ->showRelationships()
        ->not
        ->toContain('pear', 'peach')
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->showRelationships(
            $relationships,
        );

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->showRelationships()
        ->toContain('pear', 'peach')
        ->toHaveCount(2);

    expect($productSchemaInstance)
        ->showRelationships()
        ->toContain('apple', 'pear', 'peach')
        ->toHaveCount(3);
});
