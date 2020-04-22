<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Http\Response\Entrypoint;
use Symfony\Component\HttpFoundation\Response;

final class WebEntrypoint implements Entrypoint
{
    private ResponseFactory $response;
    private string $loginUri;

    public function __construct(ResponseFactory $response, string $loginUri = '/auth/login')
    {
        $this->response = $response;
        $this->loginUri = $loginUri;
    }

    public function startAuthentication(Request $request, ?AuthenticationException $exception): Response
    {
        $message = $exception->getMessage() ?? ' You must login first';

        return $this->response->redirectTo($this->loginUri)->with('message', $message);
    }
}
