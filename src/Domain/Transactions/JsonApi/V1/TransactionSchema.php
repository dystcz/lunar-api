<?php

namespace Dystcz\LunarApi\Domain\Transactions\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Transactions\Models\Transaction;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;

class TransactionSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Transaction::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            $this->idField(),

            Str::make('type'),
            Str::make('driver'),
            Boolean::make('success')
                ->serializeUsing(
                    static fn ($value) => (bool) $value,
                ),
            Number::make('amount')
                ->serializeUsing(
                    static fn ($value) => $value?->decimal,
                ),
            Str::make('status'),
            Str::make('notes'),
            Str::make('card_type'),

            ArrayHash::make('meta')
                ->hidden(),

            BelongsTo::make('currency')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this)->delimiter(','),

            ...parent::filters(),
        ];
    }
}
