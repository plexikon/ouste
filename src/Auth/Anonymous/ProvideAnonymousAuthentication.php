<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Anonymous;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\AuthenticationProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

final class ProvideAnonymousAuthentication implements AuthenticationProvider
{
    private string $anonymousContext;

    public function __construct(string $anonymousContext)
    {
        $this->anonymousContext = $anonymousContext;
    }

    public function authenticateToken(Tokenable $token): Tokenable
    {
        $token = new GenericAnonymousToken();
        $token->withContext($this->anonymousContext);
        $token->withUser(new AnonymousIdentifier());
        $token->setAuthenticated(true);

        return $token;
    }

    public function supportToken(Tokenable $token): bool
    {
        return $token instanceof GenericAnonymousToken
            && $token->getContext() === $this->anonymousContext;
    }
}
