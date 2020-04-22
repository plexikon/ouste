<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Http\Middleware\AuthenticationEventGuard;
use Plexikon\Ouste\Support\Http\Middleware\HasGuard;
use Symfony\Component\HttpFoundation\Response;

trait HasAuthenticationGuard
{
    use HasGuard;

    public function authenticate(Request $request, Closure $next)
    {
        if ($this->needAuthentication($request)) {
            $response = null;

            try {
                $response = $this->processAuthentication($request);
            } catch (AuthenticationException $exception) {
                if ($this instanceof AuthenticationEventGuard) {
                    $this->fireFailureLoginEvent($request, $exception);
                }

                return $this->guard->startAuthentication($request, $exception);
            }
        }

        return $response ?? $next($request);
    }

    abstract protected function needAuthentication(Request $request): bool;

    abstract protected function processAuthentication(Request $request): ?Response;
}
