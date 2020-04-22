<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Middleware;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

interface AuthenticationEventGuard extends AuthenticationGuard
{
    /**
     * Dispatch attempt login event
     *
     * @param Request $request
     * @param Tokenable $token
     */
    public function fireAttemptLoginEvent(Request $request, Tokenable $token): void;

    /**
     * Dispatch success login event
     *
     * @param Request $request
     * @param Tokenable $token
     */
    public function fireSuccessLoginEvent(Request $request, Tokenable $token): void;

    /**
     * Dispatch failure login event
     *
     * @param Request $request
     * @param AuthenticationException $exception
     */
    public function fireFailureLoginEvent(Request $request, AuthenticationException $exception): void;
}
