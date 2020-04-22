<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Anonymous;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Http\Middleware\HasAuthenticationGuard;
use Plexikon\Ouste\Support\Contracts\Http\Middleware\AuthenticationGuard;
use Symfony\Component\HttpFoundation\Response;

final class AnonymousAuthentication implements AuthenticationGuard
{
    use HasAuthenticationGuard;

    private string $anonymousContext;

    public function __construct(string $anonymousContext)
    {
        $this->anonymousContext = $anonymousContext;
    }

    protected function needAuthentication(Request $request): bool
    {
        return $this->guard->isStorageEmpty();
    }

    protected function processAuthentication(Request $request): ?Response
    {
        try {
            $token = new GenericAnonymousToken();

            $token->withContext($this->anonymousContext);
            $token->withUser(new AnonymousIdentifier());
            $token->setAuthenticated(true);

            $this->guard->storeAuthenticatedToken($token);
        } catch (AuthenticationException $exception) {
            //
        }

        return null;
    }
}
