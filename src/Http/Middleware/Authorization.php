<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Exception\AuthorizationDenied;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\AuthorizationStrategy;

class Authorization
{
    private AuthorizationStrategy $authorizationStrategy;
    private TokenStorage $tokenStorage;

    public function __construct(AuthorizationStrategy $authorizationStrategy, TokenStorage $tokenStorage)
    {
        $this->authorizationStrategy = $authorizationStrategy;
        $this->tokenStorage = $tokenStorage;
    }

    public function handle(Request $request, Closure $next, string ...$attributes)
    {
        if (!$token = $this->tokenStorage->getToken()) {
            throw AuthenticationServiceFailure::credentialsNotFound();
        }

        if ($attributes && !$this->authorizationStrategy->decide($token, $attributes, $request)) {
            throw AuthorizationDenied::reason();
        }

        return $next($request);
    }
}
