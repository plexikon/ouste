<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Service;

use Illuminate\Http\Request;
use Plexikon\Ouste\Auth\Recaller\GenericRecallerToken;
use Plexikon\Ouste\Auth\Recaller\Value\RecallerId;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\Recaller;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerUser;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Symfony\Component\HttpFoundation\Response;

final class PersistentTokenRecallerService extends RecallerService
{
    protected function processAutoLogin(Recaller $recaller, Request $request): Tokenable
    {
        $user = $this->recallerProvider->identityOfRecaller($recaller->token());

        if ($user->getIdentifier()->getValue() !== $recaller->id()) {
            throw new AuthenticationException("Cookie may have been tampered");
        }

        $token = new GenericRecallerToken($user->getRoles());
        $token->withUser($this->refreshIdentity($user));
        $token->withContext($this->context);

        return $token;
    }

    protected function onLoginSuccess(Request $request, Response $response, Tokenable $token): void
    {
        $this->refreshIdentity($token->getUser());
    }

    /**
     * @param User $user
     * @return User
     */
    protected function refreshIdentity(User $user): User
    {
        $recallerIdentifier = RecallerId::nextIdentity();

        /** @var RecallerUser|User $refreshedUser */
        $refreshedUser = $this->recallerProvider->refreshIdentityRecaller($user, $recallerIdentifier);

        $this->generateCookie(
            $user->getIdentifier()->getValue(),
            $refreshedUser->getRecallerIdentifier()
        );

        return $refreshedUser;
    }
}
