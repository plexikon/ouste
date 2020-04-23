<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Exception;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Plexikon\Ouste\Domain\User\Exception\UserStatusException;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Exception\AuthorizationException;
use Plexikon\Ouste\Exception\InsufficientAuthentication;
use Plexikon\Ouste\Support\Contracts\Exception\OusteException;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TrustResolver;
use Plexikon\Ouste\Support\Contracts\Http\Response\AccessDenied;
use Plexikon\Ouste\Support\Contracts\Http\Response\Entrypoint;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class FirewallExceptionHandler
{
    private ?Container $container;
    private TokenStorage $tokenStorage;
    private TrustResolver $trustResolver;
    private string $context;
    private bool $stateless;
    private ?Entrypoint $entrypoint;
    private ?AccessDenied $accessDenied;

    public function __construct(TokenStorage $tokenStorage,
                                TrustResolver $trustResolver,
                                string $context,
                                bool $stateless,
                                ?Entrypoint $entrypoint,
                                ?AccessDenied $accessDenied)
    {
        $this->tokenStorage = $tokenStorage;
        $this->trustResolver = $trustResolver;
        $this->context = $context;
        $this->stateless = $stateless;
        $this->entrypoint = $entrypoint;
        $this->accessDenied = $accessDenied;
    }

    /**
     * @param Request $request
     * @param OusteException|Throwable $exception
     * @return Response
     */
    public function handle(Request $request, OusteException $exception): Response
    {
        if ($exception instanceof AuthenticationException) {
            return $this->onAuthenticationException($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->onAuthorizationException($request, $exception);
        }

        throw new HttpException(500, "Internal server error", $exception);
    }

    protected function onAuthenticationException(Request $request, AuthenticationException $exception): Response
    {
        return $this->startAuthentication($request, $exception);
    }

    protected function onAuthorizationException(Request $request, AuthorizationException $exception): Response
    {
        $token = $this->tokenStorage->getToken();

        if (!$this->trustResolver->isFullyAuthenticated($token)) {
            return $this->whenIdentityIsNotFullyAuthenticated($request, $exception);
        }

        return $this->whenIdentityIsNotGranted($request, $exception);
    }

    protected function whenIdentityIsNotGranted(Request $request, AuthorizationException $exception): Response
    {
        if (!$this->accessDenied) {
            throw $exception;
        }

        return $this->accessDenied->onAuthorizationDenied($request, $exception);
    }

    protected function whenIdentityIsNotFullyAuthenticated(Request $request, AuthorizationException $exception): Response
    {
        $exception = InsufficientAuthentication::fromAuthorization($exception);

        return $this->startAuthentication($request, $exception);
    }

    protected function startAuthentication(Request $request, AuthenticationException $exception): Response
    {
        if (!$this->entrypoint) {
            throw $exception;
        }

        if ($exception instanceof UserStatusException) {
            $this->tokenStorage->clear();
        }

        return $this->entrypoint->startAuthentication($request, $exception);
    }

    public function setContainer(Container $app): void
    {
        $this->container = $app;
    }
}
