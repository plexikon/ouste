<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller;

use Plexikon\Ouste\Support\Contracts\Domain\User\UserChecker;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\AuthenticationProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\RecallerToken;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

final class ProvideRecallerAuthentication implements AuthenticationProvider
{
    private UserChecker $userChecker;
    private string $context;

    public function __construct(UserChecker $userChecker, string $context)
    {
        $this->userChecker = $userChecker;
        $this->context = $context;
    }

    public function authenticateToken(Tokenable $token): Tokenable
    {
       $user = $token->getUser();

       $this->userChecker->onPreAuthentication($user);

       $authenticatedToken = new GenericRecallerToken($user->getRoles());
       $authenticatedToken->withUser($user);
       $authenticatedToken->withContext($this->context);

       return $token;
    }

    public function supportToken(Tokenable $token): bool
    {
        return $token instanceof RecallerToken &&
            $token->getContext() === $this->context;
    }
}
