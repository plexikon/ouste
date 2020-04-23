<?php

namespace Plexikon\Ouste\Support\Contracts\Auth\Recaller;

use Illuminate\Http\Request;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Symfony\Component\HttpFoundation\Response;

interface Recallable
{
    /**
     * @param Request $request
     * @return Tokenable|null
     */
    public function autoLogin(Request $request): ?Tokenable;

    /**
     * @param Request $request
     */
    public function loginFail(Request $request): void;

    /**
     * @param Request $request
     * @param Response $response
     * @param Tokenable $token
     */
    public function loginSuccess(Request $request, Response $response, Tokenable $token): void;
}
