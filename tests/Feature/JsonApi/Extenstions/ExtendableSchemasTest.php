<?php

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->server = App::make(ServerMock::class, ['name' => 'v1']);
});

test('schema eager loading can be extended', function () {
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    expect($mockSchemaInstance)
        ->with()
        ->not
        ->toContain('else')
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)->setWith([
        'something',
        'else',
        'else',
    ]);

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->with()->resolve()
        ->toContain('something', 'else');

    expect($mockSchemaInstance)
        ->with()
        ->toBe([
            'something',
            'else',
        ]);
});

test('schema fields can be extended', function () {
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $field = Str::make('my_product_name');

    expect($mockSchemaInstance)
        ->fields()
        ->not
        ->toContain($field)
        ->toHaveCount(2);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->setFields([
            $field,
        ]);

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->fields()->resolve()
        ->toContain($field)
        ->toHaveCount(1);

    expect($mockSchemaInstance)
        ->fields()
        ->toContain($field)
        ->toHaveCount(3);
});

test('schema filters can be extended', function () {
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $filter = Where::make('price');

    expect($mockSchemaInstance)
        ->filters()
        ->not
        ->toContain($filter)
        ->toHaveCount(2);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->setFilters([
            $filter,
        ]);

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->filters()->resolve()
        ->toContain($filter)
        ->toHaveCount(1);

    expect($mockSchemaInstance)
        ->filters()
        ->toContain($filter)
        ->toHaveCount(3);
});

test('schema sortables can be extended', function () {
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $sortables = ['nazdar', 'cau'];

    expect($mockSchemaInstance)
        ->sortables()
        ->not
        ->toContain('nazdar', 'cau')
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->setSortables(
            $sortables,
        );

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->sortables()->resolve()
        ->toContain('nazdar', 'cau')
        ->toHaveCount(2);

    expect($mockSchemaInstance)
        ->sortables()
        ->toContain('ahoj', 'nazdar', 'cau')
        ->toHaveCount(3);
});

test('schema related gate ability can be extended', function () {
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $related = ['two', 'three', 'four', 'one'];

    expect($mockSchemaInstance)
        ->showRelated()
        ->not
        ->toContain('two', 'three', 'four')
        ->toHaveCount(1)
        ->toContain('one');

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->setShowRelated(
            $related,
        );

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->showRelated()->resolve()
        ->toContain('two', 'three', 'four', 'one')
        ->toHaveCount(4);

    expect($mockSchemaInstance)
        ->showRelated()
        ->toContain('two', 'three', 'four', 'one')
        ->toHaveCount(4);
});

test('schema relationships gate ability can be extended', function () {
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $relationships = ['pear', 'peach'];

    expect($mockSchemaInstance)
        ->showRelationship()
        ->not
        ->toContain('pear', 'peach')
        ->toHaveCount(1);

    SchemaManifest::for(ExtendableSchemasMock::class)
        ->setShowRelationship(
            $relationships,
        );

    expect(SchemaManifest::for(ExtendableSchemasMock::class))
        ->showRelationship()->resolve()
        ->toContain('pear', 'peach')
        ->toHaveCount(2);

    expect($mockSchemaInstance)
        ->showRelationship()
        ->toContain('apple', 'pear', 'peach')
        ->toHaveCount(3);
});
