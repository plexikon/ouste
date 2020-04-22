<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthorizationException;
use Plexikon\Ouste\Support\Contracts\Http\Response\AccessDenied;
use Symfony\Component\HttpFoundation\Response;

final class UnauthorizedJsonResponse implements AccessDenied
{
    private ResponseFactory $response;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function onAuthorizationDenied(Request $request, AuthorizationException $exception): Response
    {
        return $this->response->json([
            'message' => $exception->getMessage(),
            'code' => Response::HTTP_UNAUTHORIZED,
            'current' => $request->url()
        ], Response::HTTP_UNAUTHORIZED);
    }
}
