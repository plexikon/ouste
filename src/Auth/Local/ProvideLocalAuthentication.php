<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Local;

use Plexikon\Ouste\Support\Auth\LocalAuthenticationProvider;
use Plexikon\Ouste\Support\Contracts\Domain\User\LocalUser;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\LocalToken;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

final class ProvideLocalAuthentication extends LocalAuthenticationProvider
{
    protected function createAuthenticatedToken(LocalUser $user, LocalToken $token): Tokenable
    {
        $roles = $this->mergeDynamicRoles($user, $token);

        $authenticatedToken = new GenericLocalToken($roles);
        $authenticatedToken->withUser($user);
        $authenticatedToken->withCredentials($user->getPassword());
        $authenticatedToken->withContext($this->context);

        return $this->tokenDecorator->decorate($authenticatedToken);
    }

    public function supportToken(Tokenable $token): bool
    {
        return $token instanceof GenericLocalToken && $token->getContext() === $this->context;
    }
}
