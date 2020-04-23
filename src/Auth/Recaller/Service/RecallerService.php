<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Service;

use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Auth\Logout\Logout;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\Recallable;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\Recaller;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerIdentifier;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerManager;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerProvider;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Symfony\Component\HttpFoundation\Response;

abstract class RecallerService implements Recallable, Logout
{
    protected RecallerProvider $recallerProvider;
    protected string $context;
    private RecallerManager $recallerManager;
    private bool $alwaysRememberMe;

    public function __construct(RecallerManager $recallerManager,
                                RecallerProvider $recallerProvider,
                                string $context,
                                bool $alwaysRememberMe)
    {
        $this->recallerManager = $recallerManager;
        $this->recallerProvider = $recallerProvider;
        $this->context = $context;
        $this->alwaysRememberMe = $alwaysRememberMe;
    }

    public function autoLogin(Request $request): ?Tokenable
    {
        try {
            if (!$recaller = $this->recallerManager->extract($request)) {
                return null;
            }

            return $this->processAutoLogin($recaller, $request);

        } catch (AuthenticationException $exception) {
            $this->recallerManager->forget($request);

            return null;
        }
    }

    public function loginSuccess(Request $request, Response $response, Tokenable $token): void
    {
        $this->recallerManager->forget($request);

        if (!$token->getUser() instanceof User || !$this->isRememberMeRequested($request)) {
            return;
        }

        $this->onLoginSuccess($request, $response, $token);
    }

    public function logout(Request $request, Tokenable $token, Response $response): void
    {
        $this->recallerManager->forget($request);
    }

    public function loginFail(Request $request): void
    {
        $this->recallerManager->forget($request);
    }

    abstract protected function processAutoLogin(Recaller $recaller, Request $request): Tokenable;

    abstract protected function onLoginSuccess(Request $request, Response $response, Tokenable $token): void;

    /**
     * @param string $id
     * @param RecallerIdentifier $token
     */
    protected function generateCookie(string $id, RecallerIdentifier $token): void
    {
        $this->recallerManager->queue($id, $token->getValue());
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isRememberMeRequested(Request $request): bool
    {
        if ($this->alwaysRememberMe) {
            return true;
        }

        return $request->isMethod('post')
            && in_array(
                $request->get('remember-me'),
                ['true', '1', 'on', 'remember-me'],
                true
            );
    }
}
