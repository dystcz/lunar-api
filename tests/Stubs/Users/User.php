<?php

namespace Dystcz\LunarApi\Tests\Stubs\Users;

use Dystcz\LunarApi\Domain\Users\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Base\Traits\LunarUser;

class User extends Authenticatable
{
    use HasFactory;
    use LunarUser;

    protected $guarded = [];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
