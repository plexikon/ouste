<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Client\Request;
use Plexikon\Ouste\Exception\AuthorizationException;
use Plexikon\Ouste\Support\Contracts\Http\Response\AccessDenied;
use Symfony\Component\HttpFoundation\Response;

final class UnauthorizedWebResponse implements AccessDenied
{
    private ResponseFactory $response;
    private string $safeUri;

    public function __construct(ResponseFactory $response, string $safeUrl = '/')
    {
        $this->response = $response;
        $this->safeUri = $safeUrl;
    }

    public function onAuthorizationDenied(Request $request, AuthorizationException $exception): Response
    {
        return $this->response
            ->redirectTo($this->safeUri)
            ->with('message', $exception->getMessage());
    }
}
