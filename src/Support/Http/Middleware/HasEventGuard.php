<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Support\Http\Middleware;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Guardable;

trait HasEventGuard
{
    protected ?Guardable $guard;

    public function fireAttemptLoginEvent(Request $request, Tokenable $token): void
    {
        //$this->guard->fireAuthenticationEvent(new IdentityAttemptToLoggedIn($request, $token));
    }

    public function fireSuccessLoginEvent(Request $request, Tokenable $token): void
    {
        //$this->guard->fireAuthenticationEvent(new IdentitySucceedToLoggedIn($request, $token));
    }

    public function fireFailureLoginEvent(Request $request, AuthenticationException $exception): void
    {
        //$this->guard->fireAuthenticationEvent(new IdentityFailedToLoggedIn($request, $exception));
    }
}
