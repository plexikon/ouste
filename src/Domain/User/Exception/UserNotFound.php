<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\User\Exception;

use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

class UserNotFound extends AuthenticationException
{
    public static function withIdentifier(Identifier $identifier): self
    {
        return new self("Identity {$identifier->getValue()} not found");
    }

    public static function hideBadCredentials(Identifier $identifier, $exception): self
    {
        return new self("Identity {$identifier->getValue()} not found", 0, $exception);
    }
}
