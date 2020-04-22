<?php

namespace Plexikon\Ouste\Support\Contracts\Guard;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Symfony\Component\HttpFoundation\Response;

interface Guardable
{
    /**
     * Send the token to be authenticated
     *
     * @param Tokenable $token
     * @return Tokenable
     */
    public function authenticateToken(Tokenable $token): Tokenable;

    /**
     * Authenticate and store the token
     *
     * @param Tokenable $token
     * @return Tokenable
     */
    public function storeAuthenticatedToken(Tokenable $token): Tokenable;

    /**
     * Return to an entry point on authentication failure
     *
     * @param Request $request
     * @param AuthenticationException|null $exception
     * @return Response
     */
    public function startAuthentication(Request $request, ?AuthenticationException $exception): Response;

    /**
     * Dispatch authentication event
     *
     * @param mixed $authenticationEvent
     * @param array $payload
     * @param bool $halt
     * @return array|null
     */
    public function fireAuthenticationEvent($authenticationEvent, array $payload = [], bool $halt = false);

    /**
     * Check if the token storage is empty
     *
     * @return bool
     */
    public function isStorageEmpty(): bool;

    /**
     * Check if the token storage has any token
     *
     * @return bool
     */
    public function isStorageFilled(): bool;

    /**
     * Clear the token storage
     */
    public function clearStorage(): void;

    /**
     * Return a token instance if exists
     *
     * @return Tokenable|null
     */
    public function getToken(): ?Tokenable;

    /**
     * Store or clear token on storage
     *
     * @param Tokenable $token
     */
    public function storeToken(?Tokenable $token): void;
}
