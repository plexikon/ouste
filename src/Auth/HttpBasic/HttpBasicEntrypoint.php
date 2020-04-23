<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\HttpBasic;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Http\Response\Entrypoint;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class HttpBasicEntrypoint implements Entrypoint
{
    private ResponseFactory $responseFactory;
    private string $realmName;

    public function __construct(ResponseFactory $responseFactory,
                                string $realmName = 'Private access')
    {
        $this->responseFactory = $responseFactory;
        $this->realmName = $realmName;
    }

    public function startAuthentication(Request $request, ?AuthenticationException $exception): Response
    {
        $headers = ['WWW-authenticate' => sprintf('Basic realm="%s"', $this->realmName)];

        throw new HttpException(
            Response::HTTP_UNAUTHORIZED,
            $exception ? $exception->getMessage() : null,
            $exception,
            $headers
        );
    }
}
