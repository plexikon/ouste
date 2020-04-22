<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Response;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

interface AccessDenied
{
    /**
     * @param Request $request
     * @param AuthorizationException $exception
     * @return Response
     */
    public function onAuthorizationDenied(Request $request, AuthorizationException $exception): Response;
}
