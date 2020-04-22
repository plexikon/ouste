<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Local;

use Illuminate\Http\Request;
use Plexikon\Ouste\Http\Middleware\HasAuthenticationGuard;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Http\Middleware\AuthenticationEventGuard;
use Plexikon\Ouste\Support\Contracts\Http\Middleware\StatefulAuthentication;
use Plexikon\Ouste\Support\Contracts\Http\Request\AuthenticationRequest;
use Plexikon\Ouste\Support\Contracts\Http\Response\AuthenticationResponse;
use Plexikon\Ouste\Support\Http\Middleware\HasEventGuard;
use Plexikon\Ouste\Support\Http\Middleware\HasStatefulAuthentication;
use Symfony\Component\HttpFoundation\Response;

class LocalAuthentication implements AuthenticationEventGuard, StatefulAuthentication
{
    use HasAuthenticationGuard, HasEventGuard, HasStatefulAuthentication;

    private AuthenticationRequest $authRequest;
    private AuthenticationResponse $authResponse;
    private string $context;

    public function __construct(AuthenticationRequest $authRequest,
                                AuthenticationResponse $authResponse,
                                string $context)
    {
        $this->authRequest = $authRequest;
        $this->authResponse = $authResponse;
        $this->context = $context;
    }

    protected function needAuthentication(Request $request): bool
    {
        return $this->guard->isStorageEmpty() && $this->authRequest->match($request);
    }

    protected function processAuthentication(Request $request): ?Response
    {
        $token = $this->createLocalToken($request);

        $this->fireAttemptLoginEvent($request, $token);

        $authToken = $this->guard->storeAuthenticatedToken($token);

        return $this->onAuthenticationSuccess($request, $authToken);
    }

    protected function createLocalToken(Request $request): Tokenable
    {
        [$identifier, $password] = $this->authRequest->extractCredentials($request);

        $token = new GenericLocalToken();
        $token->withContext($this->context);
        $token->withUser($identifier);
        $token->withCredentials($password);

        return $token;
    }

    protected function onAuthenticationSuccess(Request $request, Tokenable $token): Response
    {
        $this->fireSuccessLoginEvent($request, $token);

        $response = $this->authResponse->onSuccess($request, $token);

        if ($this->recallerService) {
            //
        }

        return $response;
    }
}
