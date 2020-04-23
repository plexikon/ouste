<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\Recallable;
use Plexikon\Ouste\Support\Contracts\Http\Middleware\AuthenticationEventGuard;
use Plexikon\Ouste\Support\Http\Middleware\HasAuthenticationGuard;
use Plexikon\Ouste\Support\Http\Middleware\HasEventGuard;
use Symfony\Component\HttpFoundation\Response;

final class RecallerAuthentication implements AuthenticationEventGuard
{
    use HasAuthenticationGuard, HasEventGuard;

    private Recallable $recaller;

    public function __construct(Recallable $recaller)
    {
        $this->recaller = $recaller;
    }

    protected function processAuthentication(Request $request): ?Response
    {
        if (!$recallerToken = $this->recaller->autoLogin($request)) {
            return null;
        }

        try {
            $this->fireAttemptLoginEvent($request, $recallerToken);

            $authenticatedToken = $this->guard->storeAuthenticatedToken($recallerToken);

            $this->fireSuccessLoginEvent($request, $authenticatedToken);

            return null;
        } catch (AuthenticationException $exception) {
            $this->recaller->loginFail($request);

            $this->fireFailureLoginEvent($request, $exception);

            throw $exception;
        }
    }

    protected function needAuthentication(Request $request): bool
    {
        return $this->guard->isStorageEmpty();
    }
}
