<?php

namespace Dystcz\LunarApi\Domain\Addresses\JsonApi\V1;

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;

class AddressSchema extends Schema
{
    /**
     * The default paging parameters to use if the client supplies none.
     */
    protected ?array $defaultPagination = ['number' => 1];

    public function indexQuery(?Request $request, Builder $query): Builder
    {
        return $query->whereIn('customer_id', Auth::user()->customers->pluck('id'));
    }

    /**
     * The model the schema corresponds to.
     */
    public static string $model = Address::class;

    /**
     * The relationships that should always be eager loaded.
     */
    public function with(): array
    {
        return [
            ...parent::with(),
        ];
    }

    /**
     * Get the include paths supported by this resource.
     *
     * @return string[]|iterable
     */
    public function includePaths(): iterable
    {
        return [
            ...parent::includePaths(),
            'country',
            'customer',
        ];
    }

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ...parent::fields(),

            ID::make(),

            Str::make('title'),
            Str::make('first_name'),
            Str::make('last_name'),
            Str::make('company_name'),
            Str::make('line_one'),
            Str::make('line_two'),
            Str::make('line_three'),
            Str::make('city'),
            Str::make('state'),
            Str::make('postcode'),
            Str::make('delivery_instructions'),
            Str::make('contact_email'),
            Str::make('contact_phone'),

            Boolean::make('shipping_default'),
            Boolean::make('billing_default'),

            ArrayHash::make('meta')
                ->serializeUsing(fn ($value) => ! $value ? null : ((array) $value)),

            BelongsTo::make('customer'),
            BelongsTo::make('country'),
        ];
    }

    public function sortables(): iterable
    {
        return [
            ...parent::sortables(),
        ];
    }

    /**
     * Get the resource filters.
     */
    public function filters(): array
    {
        return [
            ...parent::filters(),

            WhereIdIn::make($this),
        ];
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make()
            ->withDefaultPerPage(
                Config::get('lunar-api.domains.addresses.pagination', 12)
            );
    }

    /**
     * Get the JSON:API resource type.
     */
    public static function type(): string
    {
        return 'addresses';
    }
}
