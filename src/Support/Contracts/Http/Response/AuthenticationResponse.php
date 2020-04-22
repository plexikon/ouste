<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Response;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

interface AuthenticationResponse
{
    /**
     * @param Request $request
     * @param Tokenable $token
     * @return Response
     */
    public function onSuccess(Request $request, Tokenable $token): Response;

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onFailure(Request $request, AuthenticationException $exception): Response;
}
