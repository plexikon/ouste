<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\User\Exception;

use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

class UserLocked extends UserStatusException
{
    public static function withIdentifier(Identifier $identifier): self
    {
        return new self("Identity {$identifier->getValue()} has been locked");
    }
}
