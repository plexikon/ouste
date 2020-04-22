<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Response;


use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

interface Entrypoint
{
    /**
     * @param Request $request
     * @param AuthenticationException|null $exception
     * @return Response
     */
    public function startAuthentication(Request $request, ?AuthenticationException $exception): Response;
}
