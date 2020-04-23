<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\HttpBasic;

use Illuminate\Http\Request;
use Plexikon\Ouste\Auth\Local\GenericLocalToken;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Exception\InvalidAssertion;
use Plexikon\Ouste\Support\Contracts\Domain\User\LocalUser;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Http\Middleware\AuthenticationEventGuard;
use Plexikon\Ouste\Support\Contracts\Http\Request\IdentifierCredentialsRequest;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;
use Plexikon\Ouste\Support\Http\Middleware\HasAuthenticationGuard;
use Plexikon\Ouste\Support\Http\Middleware\HasEventGuard;
use Symfony\Component\HttpFoundation\Response;

final class HttpBasicAuthentication implements AuthenticationEventGuard
{
    use HasAuthenticationGuard, HasEventGuard;

    private IdentifierCredentialsRequest $loginRequest;
    private string $context;

    public function __construct(IdentifierCredentialsRequest $loginRequest, string $context)
    {
        $this->loginRequest = $loginRequest;
        $this->context = $context;
    }

    protected function processAuthentication(Request $request): ?Response
    {
        try {
            $token = $this->createToken($request);

            $this->fireAttemptLoginEvent($request, $token);

            $authenticatedToken = $this->guard->storeAuthenticatedToken($token);

            $this->fireSuccessLoginEvent($request, $authenticatedToken);

            return null;
        } catch (AuthenticationException $exception) {
            $this->guard->clearStorage();

            $this->fireFailureLoginEvent($request, $exception);

            return $this->guard->startAuthentication($request, $exception);
        }
    }

    protected function needAuthentication(Request $request): bool
    {
        try {
            return $this->isNotAlreadyAuthenticated(
                $this->loginRequest->extractIdentifier($request),
                $this->guard->getToken()
            );
        } catch (InvalidAssertion $exception) {
            return true;
        }
    }

    protected function createToken(Request $request): GenericLocalToken
    {
        $token = new GenericLocalToken();

        $token->withUser($this->loginRequest->extractIdentifier($request));
        $token->withCredentials($this->loginRequest->extractPassword($request));
        $token->withContext($this->context);

        return $token;
    }

    protected function isNotAlreadyAuthenticated(Identifier $identifier, ?Tokenable $token): bool
    {
        return !(
            $token instanceof GenericLocalToken
            && $token->isAuthenticated()
            && $token->getUser() instanceof LocalUser
            && $token->getUser()->getIdentifier()->sameValueAs($identifier));
    }
}
