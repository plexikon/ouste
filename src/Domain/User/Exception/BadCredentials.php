<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\User\Exception;

use Plexikon\Ouste\Exception\AuthenticationException;

class BadCredentials extends AuthenticationException
{
    public static function invalid(): self
    {
        return new self('invalid credentials');
    }

    public static function hasChanged(): self
    {
        return new self("Credentials has changed between session");
    }

    public static function emptyCredentials(): self
    {
        return new self("Credentials are empty");
    }
}
