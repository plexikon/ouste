<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Context;

use Illuminate\Http\Request;
use Plexikon\Ouste\Domain\User\Exception\UserNotFound;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Http\Event\ContextEvent;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Http\Middleware\AuthenticationGuard;
use Plexikon\Ouste\Support\Http\Middleware\HasAuthenticationGuard;
use Symfony\Component\HttpFoundation\Response;

class ContextAuthentication implements AuthenticationGuard
{
    use HasAuthenticationGuard;

    private ContextEvent $contextEvent;
    private array $userProviders;

    public function __construct(ContextEvent $contextEvent, UserProvider ...$userProviders)
    {
        $this->contextEvent = $contextEvent;
        $this->userProviders = $userProviders;
    }

    protected function processAuthentication(Request $request): ?Response
    {
        try {
            $tokenString = $request->session()->get($this->contextEvent->sessionName());

            $this->handleSerializedToken($tokenString);
        } catch (AuthenticationException $exception) {
            $this->guard->clearStorage();
        }

        return null;
    }

    protected function needAuthentication(Request $request): bool
    {
        $this->guard->fireAuthenticationEvent($this->contextEvent);

        return $request->session()->has($this->contextEvent->sessionName());
    }

    protected function handleSerializedToken(string $tokenString): void
    {
        /** @var Tokenable $token */
        $token = unserialize($tokenString, [Tokenable::class]);

        $user = null;
        foreach ($this->userProviders as $userProvider){
            $currentUser = $token->getUser();

            if(!$userProvider->supports($currentUser)){
                continue;
            }

            try{
                $user = $userProvider->userOf($currentUser->getIdentifier());
                $token->withUser($user);
            }catch (UserNotFound $exception){
                return;
            }
        }

        $this->guard->storeToken($token);
    }
}
