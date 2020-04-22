<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Http\Response\AuthenticationResponse;
use Symfony\Component\HttpFoundation\Response;

final class HomeAuthenticationResponse implements AuthenticationResponse
{
    private ResponseFactory $responseFactory;
    private string $safeRoute;

    public function __construct(ResponseFactory $responseFactory, string $safeRoute = 'home')
    {
        $this->responseFactory = $responseFactory;
        $this->safeRoute = $safeRoute;
    }

    public function onSuccess(Request $request, Tokenable $token): Response
    {
        return $this->responseFactory->redirectTo($this->safeRoute);
    }

    public function onFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->responseFactory->redirectToRoute($this->safeRoute);
    }
}
