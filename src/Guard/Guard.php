<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Authenticatable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Guardable;
use Plexikon\Ouste\Support\Contracts\Http\Response\Entrypoint;
use Symfony\Component\HttpFoundation\Response;

class Guard implements Guardable
{
    private TokenStorage $tokenStorage;
    private Authenticatable $authenticationManager;
    private Dispatcher $eventDispatcher;
    private Entrypoint $entrypoint;

    public function __construct(TokenStorage $tokenStorage,
                                Authenticatable $authenticationManager,
                                Dispatcher $eventDispatcher,
                                Entrypoint $entrypoint)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->entrypoint = $entrypoint;
    }

    public function authenticateToken(Tokenable $token): Tokenable
    {
        return $this->authenticationManager->authenticateToken($token);
    }

    public function storeAuthenticatedToken(Tokenable $token): Tokenable
    {
        $this->tokenStorage->store(
            $authenticatedToken = $this->authenticateToken($token)
        );

        return $authenticatedToken;
    }

    public function storeToken(?Tokenable $token): void
    {
        $this->tokenStorage->store($token);
    }

    public function clearStorage(): void
    {
        $this->tokenStorage->clear();
    }

    public function getToken(): ?Tokenable
    {
        return $this->tokenStorage->getToken();
    }

    public function isStorageEmpty(): bool
    {
        return $this->tokenStorage->isEmpty();
    }

    public function isStorageFilled(): bool
    {
        return $this->tokenStorage->isNotEmpty();
    }

    public function startAuthentication(Request $request, ?AuthenticationException $exception): Response
    {
        return $this->entrypoint->startAuthentication($request, $exception);
    }

    public function fireAuthenticationEvent($authenticationEvent, array $payload = [], bool $halt = false)
    {
        return $this->eventDispatcher->dispatch($authenticationEvent, $payload, $halt);
    }
}
