<?php

namespace Dystcz\LunarApi\Domain\Users\Models;

use Dystcz\LunarApi\Domain\Users\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Users\Contracts\User as UserContract;
use Dystcz\LunarApi\Domain\Users\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Lunar\Base\LunarUser as LunarUserContract;
use Lunar\Base\Traits\HasModelExtending;
use Lunar\Base\Traits\LunarUser;
use Lunar\Models\Cart;
use Lunar\Models\Customer;
use Lunar\Models\Order;

class User extends Authenticatable implements LunarUserContract, UserContract
{
    use HasFactory;
    use HasModelExtending;
    use InteractsWithLunarApi;
    use LunarUser;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            ...$this->casts,
            'email_verified_at' => 'datetime',
        ];
    }

    public function getTable(): string
    {
        return 'users';
    }

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * Get full name attribute.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->first_name && ! $this->last_name) {
                    return null;
                }

                return implode(' ', array_filter([$this->first_name, $this->last_name]));
            }
        );
    }

    /**
     * @return MorphOne<Media>
     */
    public function avatar(): MorphOne
    {
        return $this
            ->morphOne(Config::get('media-library.media_model'), 'model')
            ->where('collection_name', 'avatar');
    }

    /**
     * @return BelongsToMany<Customer>
     */
    public function customers(): BelongsToMany
    {
        $prefix = Config::get('lunar.database.table_prefix');

        return $this->belongsToMany(
            Customer::modelClass(),
            "{$prefix}customer_user",
        );
    }

    /**
     * @return HasMany<Cart>
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::modelClass());
    }

    public function latestCustomer(): ?Customer
    {
        return $this
            ->customers()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::modelClass());
    }
}
