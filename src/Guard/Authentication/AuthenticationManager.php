<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication;

use Generator;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Authenticatable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\AuthenticationProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

final class AuthenticationManager implements Authenticatable
{
    private Generator $authenticationProviders;

    public function __construct(Generator $authenticationProviders)
    {
        $this->authenticationProviders = $authenticationProviders;
    }

    public function authenticateToken(Tokenable $token): Tokenable
    {
        /** @var AuthenticationProvider $authenticationProvider */
        foreach ($this->authenticationProviders as $authenticationProvider) {
            if (!$authenticationProvider->supportToken($token)) {
                continue;
            }

            return $authenticationProvider->authenticateToken($token);
        }

        throw AuthenticationServiceFailure::unsupportedToken($token);
    }
}
