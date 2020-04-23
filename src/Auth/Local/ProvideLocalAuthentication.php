<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Local;

use Plexikon\Ouste\Domain\User\Exception\BadCredentials;
use Plexikon\Ouste\Domain\User\Exception\UserNotFound;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Exception\RuntimeException;
use Plexikon\Ouste\Support\Contracts\Domain\User\LocalUser;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserChecker;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\AuthenticationProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenDecorator;
use Plexikon\Ouste\Support\Contracts\Guard\CredentialsChecker;

class ProvideLocalAuthentication implements AuthenticationProvider
{
    private UserProvider $userProvider;
    private UserChecker $userChecker;
    private CredentialsChecker $credentialsChecker;
    private TokenDecorator $tokenDecorator;
    private string $context;

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
        $user = $this->retrieveUser($token);

        try {
            $this->checkUser($user, $token);
        } catch (BadCredentials $exception) {
            throw UserNotFound::hideBadCredentials($user->getIdentifier(), $exception);
        }

        $roles = $this->mergeRoles($user, $token);

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

    private function retrieveUser(Tokenable $token): LocalUser
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

    private function checkUser(LocalUser $user, Tokenable $token): void
    {
        $this->userChecker->onPreAuthentication($user);

        $this->credentialsChecker->checkCredentials($user, $token);

        $this->userChecker->onPostAuthentication($user);
    }

    private function mergeRoles(LocalUser $user, Tokenable $token): array
    {
        $roles = $user->getRoles();

        foreach ($token->getRoles() as $role) {
            // impersonate
        }

        return $roles;
    }
}
