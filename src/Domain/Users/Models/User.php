<?php

namespace Dystcz\LunarApi\Domain\Users\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Base\Traits\LunarUser;

class User extends Authenticatable
{
    use LunarUser;

    protected $guarded = [];
}
