<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization\Voter;

use Plexikon\Ouste\Guard\Authorization\AuthenticatedToken;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TrustResolver;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\Votable;

class TokenVoter implements Votable
{
    private TrustResolver $trustResolver;

    public function __construct(TrustResolver $trustResolver)
    {
        $this->trustResolver = $trustResolver;
    }

    public function vote(Tokenable $token, iterable $attributes, object $subject): int
    {
        foreach ($attributes as $attribute) {
            if (null === $attribute || !in_array($attribute, AuthenticatedToken::getValues())) {
                continue;
            }

            return $this->isAuthenticated($token) ? self::ACCESS_GRANTED : self::ACCESS_DENIED;
        }

        return self::ACCESS_ABSTAIN;
    }

    protected function isAuthenticated(Tokenable $token): bool
    {
        return $this->trustResolver->isFullyAuthenticated($token)
            || $this->trustResolver->isRemembered($token)
            || $this->trustResolver->isAnonymous($token);
    }
}
