<?php

use Dystcz\LunarApi\Base\Manifests\SchemaManifest;
use Dystcz\LunarApi\Tests\Feature\JsonApi\Extensions\ExtendableSchemasMock;
use Dystcz\LunarApi\Tests\Feature\JsonApi\Extensions\ServerMock;
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
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    expect($mockSchemaInstance)
        ->with()
        ->not
        ->toContain('else')
        ->toHaveCount(1);

    SchemaManifest::extend(ExtendableSchemasMock::class)->setWith([
        'something',
        'else',
        'else',
    ]);

    expect(SchemaManifest::extend(ExtendableSchemasMock::class))
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
    /** @var TestCase $this */
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $field = Str::make('my_product_name');

    expect($mockSchemaInstance)
        ->fields()
        ->not
        ->toContain($field)
        ->toHaveCount(2);

    SchemaManifest::extend(ExtendableSchemasMock::class)
        ->setFields([
            $field,
        ]);

    expect(SchemaManifest::extend(ExtendableSchemasMock::class))
        ->fields()->resolve()
        ->toContain($field)
        ->toHaveCount(1);

    expect($mockSchemaInstance)
        ->fields()
        ->toContain($field)
        ->toHaveCount(3);
});

test('schema filters can be extended', function () {
    /** @var TestCase $this */
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $filter = Where::make('price');

    expect($mockSchemaInstance)
        ->filters()
        ->not
        ->toContain($filter)
        ->toHaveCount(2);

    SchemaManifest::extend(ExtendableSchemasMock::class)
        ->setFilters([
            $filter,
        ]);

    expect(SchemaManifest::extend(ExtendableSchemasMock::class))
        ->filters()->resolve()
        ->toContain($filter)
        ->toHaveCount(1);

    expect($mockSchemaInstance)
        ->filters()
        ->toContain($filter)
        ->toHaveCount(3);
});

test('schema sortables can be extended', function () {
    /** @var TestCase $this */
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $sortables = ['nazdar', 'cau'];

    expect($mockSchemaInstance)
        ->sortables()
        ->not
        ->toContain('nazdar', 'cau')
        ->toHaveCount(1);

    SchemaManifest::extend(ExtendableSchemasMock::class)
        ->setSortables(
            $sortables,
        );

    expect(SchemaManifest::extend(ExtendableSchemasMock::class))
        ->sortables()->resolve()
        ->toContain('nazdar', 'cau')
        ->toHaveCount(2);

    expect($mockSchemaInstance)
        ->sortables()
        ->toContain('ahoj', 'nazdar', 'cau')
        ->toHaveCount(3);
});

test('schema related gate ability can be extended', function () {
    /** @var TestCase $this */
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $related = ['two', 'three', 'four', 'one'];

    expect($mockSchemaInstance)
        ->showRelated()
        ->not
        ->toContain('two', 'three', 'four')
        ->toHaveCount(1)
        ->toContain('one');

    SchemaManifest::extend(ExtendableSchemasMock::class)
        ->setShowRelated(
            $related,
        );

    expect(SchemaManifest::extend(ExtendableSchemasMock::class))
        ->showRelated()->resolve()
        ->toContain('two', 'three', 'four', 'one')
        ->toHaveCount(4);

    expect($mockSchemaInstance)
        ->showRelated()
        ->toContain('two', 'three', 'four', 'one')
        ->toHaveCount(4);
});

test('schema relationships gate ability can be extended', function () {
    /** @var TestCase $this */
    $mockSchemaInstance = $this->server->schemas()->schemaFor('products');

    $relationships = ['pear', 'peach'];

    expect($mockSchemaInstance)
        ->showRelationship()
        ->not
        ->toContain('pear', 'peach')
        ->toHaveCount(1);

    SchemaManifest::extend(ExtendableSchemasMock::class)
        ->setShowRelationship(
            $relationships,
        );

    expect(SchemaManifest::extend(ExtendableSchemasMock::class))
        ->showRelationship()->resolve()
        ->toContain('pear', 'peach')
        ->toHaveCount(2);

    expect($mockSchemaInstance)
        ->showRelationship()
        ->toContain('apple', 'pear', 'peach')
        ->toHaveCount(3);
});
