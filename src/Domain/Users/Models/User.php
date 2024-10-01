<?php

namespace Dystcz\LunarApi\Domain\Users\Models;

use Dystcz\LunarApi\Domain\Users\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Users\Contracts\User as UserContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Base\Traits\LunarUser;

class User extends Authenticatable implements UserContract
{
    use InteractsWithLunarApi;
    use LunarUser;

    protected $guarded = [];
}
