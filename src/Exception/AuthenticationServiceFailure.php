<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Exception;

use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

class AuthenticationServiceFailure extends RuntimeException
{
    public static function unsupportedToken(Tokenable $token): self
    {
        $tokenClass = get_class($token);

        return new self("No authentication provider support token {$tokenClass}");
    }

    public static function unsupportedUserProvider(User $user): self
    {
        $userClass = get_class($user);

        return new self("No identity provider support identity class {$userClass}");
    }

    public static function noAuthenticationProvider(): self
    {
        return new self('No authentication provider has been registered in Authentication manager');
    }

    public static function noIdentityProvider(): self
    {
        return new self('No identity provider has been provided to context middleware');
    }

    public static function credentialsNotFound(): self
    {
        return new self("Credentials not found in storage");
    }

    public static function noAuthorizationVoters(): self
    {
        return new self('You must at least add one voter to the authorization strategy');
    }

    public static function noLogoutHandler(): self
    {
        return new self('You must at least add one logout handler to the authentication logout');
    }
}
