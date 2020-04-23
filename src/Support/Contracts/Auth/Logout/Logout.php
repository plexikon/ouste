<?php

namespace Plexikon\Ouste\Support\Contracts\Auth\Logout;

use Illuminate\Http\Request;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Symfony\Component\HttpFoundation\Response;

interface Logout
{
    /**
     * Logout the request
     *
     * @param Request $request
     * @param Tokenable $token
     * @param Response $response
     */
    public function logout(Request $request, Tokenable $token, Response $response): void;
}
