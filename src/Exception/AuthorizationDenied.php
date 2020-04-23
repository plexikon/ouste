<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Exception;

class AuthorizationDenied extends AuthorizationException
{
    public static function reason(string $message = null): AuthorizationDenied
    {
        return new self($message ?? 'Authorization denied');
    }
}
