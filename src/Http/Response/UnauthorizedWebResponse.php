<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthorizationException;
use Plexikon\Ouste\Support\Contracts\Http\Response\AccessDenied;
use Symfony\Component\HttpFoundation\Response;

final class UnauthorizedWebResponse implements AccessDenied
{
    private ResponseFactory $response;
    private string $safeRoute;

    public function __construct(ResponseFactory $response, string $safeRoute = 'home')
    {
        $this->response = $response;
        $this->safeRoute = $safeRoute;
    }

    public function onAuthorizationDenied(Request $request, AuthorizationException $exception): Response
    {
        return $this->response
            ->redirectToRoute($this->safeRoute)
            ->with('message', $exception->getMessage());
    }
}
