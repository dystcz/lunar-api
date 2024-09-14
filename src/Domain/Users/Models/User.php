<?php

namespace Dystcz\LunarApi\Domain\Users\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Base\LunarUser as LunarUserContract;
use Lunar\Base\Traits\LunarUser;

class User extends Authenticatable implements LunarUserContract
{
    use LunarUser;

    protected $guarded = [];
}
