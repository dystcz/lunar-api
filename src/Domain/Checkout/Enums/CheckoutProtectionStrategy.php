<?php

namespace Dystcz\LunarApi\Domain\Checkout\Enums;

enum CheckoutProtectionStrategy: string
{
    // Routes are protected by a signature
    case SIGNATURE = 'signature';

    // Routes are protected by an authentication
    case AUTH = 'auth';

    // Routes are not protected
    case NONE = '';
}
