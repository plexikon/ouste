<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Response;

use Illuminate\Http\Request;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Symfony\Component\HttpFoundation\Response;

interface LogoutResponse
{
    /**
     * @param Request $request
     * @param Tokenable $token
     * @return Response
     */
    public function onLogout(Request $request, Tokenable $token): Response;
}
