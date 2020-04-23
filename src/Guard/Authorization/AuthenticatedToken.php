<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization;

use MabeEnum\Enum;

/**
 * @method AuthenticatedToken AUTHENTICATED_FULLY()
 * @method AuthenticatedToken AUTHENTICATED_REMEMBERED()
 * @method AuthenticatedToken AUTHENTICATED_ANONYMOUSLY()
 */
class AuthenticatedToken extends Enum
{
    public const AUTHENTICATED_FULLY = 'is_fully_authenticated_token';

    public const AUTHENTICATED_REMEMBERED = 'is_remembered_token';

    public const AUTHENTICATED_ANONYMOUSLY = 'is_anonymous_token';
}
