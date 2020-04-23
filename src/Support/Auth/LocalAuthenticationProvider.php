<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Support\Auth;

use Plexikon\Ouste\Domain\User\Exception\BadCredentials;
use Plexikon\Ouste\Domain\User\Exception\UserNotFound;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Support\Contracts\Domain\User\LocalUser;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserChecker;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\AuthenticationProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\LocalToken;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenDecorator;
use Plexikon\Ouste\Support\Contracts\Guard\CredentialsChecker;

abstract class LocalAuthenticationProvider implements AuthenticationProvider
{
    private UserProvider $userProvider;
    private UserChecker $userChecker;
    private CredentialsChecker $credentialsChecker;
    protected TokenDecorator $tokenDecorator;
    protected string $context;

    public function __construct(UserProvider $userProvider,
                                UserChecker $userChecker,
                                CredentialsChecker $credentialsChecker,
                                TokenDecorator $tokenDecorator,
                                string $context)
    {
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->credentialsChecker = $credentialsChecker;
        $this->tokenDecorator = $tokenDecorator;
        $this->context = $context;
    }

    public function authenticateToken(Tokenable $token): Tokenable
    {
        if (!$token instanceof LocalToken) {
            throw new AuthenticationServiceFailure("Token must ne an insane of local token");
        }

        $user = $this->retrieveUser($token);

        try {
            $this->checkUser($user, $token);
        } catch (BadCredentials $exception) {
            throw UserNotFound::hideBadCredentials($user->getIdentifier(), $exception);
        }

        return $this->createAuthenticatedToken($user, $token);
    }

    private function retrieveUser(LocalToken $token): LocalUser
    {
        $identifier = $token->getUser();

        if ($identifier instanceof LocalUser) {
            return $identifier;
        }

        $user = $this->userProvider->userOf($identifier);

        if (!$user instanceof LocalUser) {
            throw new AuthenticationServiceFailure("User provider must return a local user");
        }

        return $user;
    }

    private function checkUser(LocalUser $user, LocalToken $token): void
    {
        $this->userChecker->onPreAuthentication($user);

        $this->credentialsChecker->checkCredentials($user, $token);

        $this->userChecker->onPostAuthentication($user);
    }

    protected function mergeDynamicRoles(LocalUser $user, LocalToken $token): array
    {
        $roles = $user->getRoles();

        foreach ($token->getRoles() as $role) {
            // impersonate
        }

        return $roles;
    }

    abstract protected function createAuthenticatedToken(LocalUser $user, LocalToken $token): Tokenable;
}
