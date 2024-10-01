<?php

namespace Dystcz\LunarApi\Domain\Carts\Contracts;

use Lunar\Models\Contracts\Cart as LunarCart;

interface Cart extends CurrentSessionCart, LunarCart {}
