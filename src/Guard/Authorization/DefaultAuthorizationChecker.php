<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Authenticatable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\AuthorizationChecker;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\AuthorizationStrategy;

final class DefaultAuthorizationChecker implements AuthorizationChecker
{
    private ?Request $request;
    private Authenticatable $authenticationManager;
    private AuthorizationStrategy $authorizationStrategy;
    private TokenStorage $tokenStorage;
    private bool $alwaysAuthenticate;

    public function __construct(Authenticatable $authenticationManager,
                                AuthorizationStrategy $authorizationStrategy,
                                TokenStorage $tokenStorage,
                                bool $alwaysAuthenticate = false)
    {
        $this->authenticationManager = $authenticationManager;
        $this->authorizationStrategy = $authorizationStrategy;
        $this->tokenStorage = $tokenStorage;
        $this->alwaysAuthenticate = $alwaysAuthenticate;
    }

    public function isGranted(iterable $attributes, ?object $subject): bool
    {
        if (!$token = $this->tokenStorage->getToken()) {
            throw AuthenticationServiceFailure::credentialsNotFound();
        }

        $token = $this->authenticateToken($token);

        return $this->authorizationStrategy->decide($token, $attributes, $subject ?? $this->request);
    }

    public function isNotGranted(iterable $attributes, ?object $subject): bool
    {
        return !$this->isGranted($attributes, $subject);
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    private function authenticateToken(Tokenable $token): Tokenable
    {
        if ($this->alwaysAuthenticate || !$token->isAuthenticated()) {
            $token = $this->authenticationManager->authenticateToken($token);

            $this->tokenStorage->store($token);
        }

        return $token;
    }
}
