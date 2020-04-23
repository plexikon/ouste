<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Exception;

use Symfony\Component\HttpFoundation\Response;

class InsufficientAuthentication extends AuthenticationException
{
    public static function fromAuthorization(AuthorizationException $exception): self
    {
        $message = "Full authentication is required to access this resource";

        return new self($message, Response::HTTP_UNAUTHORIZED, $exception);
    }
}
