<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Support\Http;

use Illuminate\Contracts\Container\Container;
use LogicException;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Exception\AuthorizationDenied;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TrustResolver;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\AuthorizationChecker;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

trait HasAuthorization
{
    private Container $container;

    /**
     * Grant token|user with permissions|roles and a subject
     *
     * @param string|array $attributes
     * @param object|null $subject
     * @return bool
     */
    protected function isGranted($attributes, object $subject = null): bool
    {
        return $this->authorizationChecker()->isGranted((array)$attributes, $subject);
    }

    /**
     * Grant access or raise an authorization exception
     *
     * @param string|array $attributes
     * @param object|null $subject
     * @return bool
     * @throws AuthorizationDenied
     */
    protected function denyAccessUnlessGranted($attributes, object $subject = null): bool
    {
        if (!$this->isGranted($attributes, $subject)) {
            $this->raiseAuthorizationDenied();
        }

        return true;
    }

    /**
     * Shortcut to get user from an authenticated token
     *
     * @return User
     * @throws AuthenticationServiceFailure
     * @throws LogicException
     */
    protected function getAuthenticatedUser(): User
    {
        $token = $this->requireToken();

        if ($this->isFullyAuthenticatedUser() || $this->isRememberedUser()) {
            return $token->getUser();
        }

        throw new LogicException("You must check first if token is not anonymous");
    }

    /**
     * Shortcut to get identifier from authenticated identity
     *
     * @return Identifier
     */
    protected function getUserIdentifier(): Identifier
    {
        return $this->getAuthenticatedUser()->getIdentifier();
    }

    /**
     * Check if token is anonymous
     *
     * @return bool
     */
    protected function isAnonymousIdentity(): bool
    {
        return $this->trustResolver()->isAnonymous($this->requireToken());
    }

    /**
     * Check if User is remembered
     *
     * @return bool
     */
    protected function isRememberedUser(): bool
    {
        return $this->trustResolver()->isRemembered($this->requireToken());
    }

    /**
     * Check if User is fully authenticated
     *
     * @return bool
     */
    protected function isFullyAuthenticatedUser(): bool
    {
        return $this->trustResolver()->isFullyAuthenticated($this->requireToken());
    }

    /**
     * Request token from token storage or raise exception
     *
     * @return Tokenable
     * @throws AuthenticationServiceFailure
     */
    protected function requireToken(): Tokenable
    {
        if ($token = $this->tokenStorage()->getToken()) {
            return $token;
        }

        throw AuthenticationServiceFailure::credentialsNotFound();
    }

    /**
     * @return TokenStorage
     */
    protected function tokenStorage(): TokenStorage
    {
        return $this->container()->get(TokenStorage::class);
    }

    /**
     * @return AuthorizationChecker
     */
    protected function authorizationChecker(): AuthorizationChecker
    {
        return $this->container()->get(AuthorizationChecker::class);
    }

    /**
     * @return TrustResolver
     */
    protected function trustResolver(): TrustResolver
    {
        return $this->container()->get(TrustResolver::class);
    }

    /**
     * @param string|null $message
     * @return AuthorizationDenied
     */
    protected function raiseAuthorizationDenied(string $message = null): AuthorizationDenied
    {
        throw AuthorizationDenied::reason($message);
    }

    /**
     * @return Container
     */
    private function container(): Container
    {
        return $this->container ?? $this->container = app();
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
