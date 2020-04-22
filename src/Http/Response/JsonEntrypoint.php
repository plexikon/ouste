<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Http\Response\Entrypoint;
use Symfony\Component\HttpFoundation\Response;

final class JsonEntrypoint implements Entrypoint
{
    private ResponseFactory $response;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function startAuthentication(Request $request, ?AuthenticationException $exception): Response
    {
        $message = $exception->getMessage() ?? ' You must login first';

        return $this->response->json([
            'message' => $message,
            'code' => Response::HTTP_FORBIDDEN,
            'current' => $request->url()
        ], Response::HTTP_FORBIDDEN);
    }
}
