<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization\Strategy;

use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\AuthorizationStrategy;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\Votable;

final class UnanimousAuthorization implements AuthorizationStrategy
{
    private iterable $voters;
    private bool $allowIfAllAbstain;

    public function __construct(iterable $voters, bool $allowIfAllAbstain)
    {
        if (empty($voters)) {
            throw AuthenticationServiceFailure::noAuthorizationVoters();
        }

        $this->voters = $voters;
        $this->allowIfAllAbstain = $allowIfAllAbstain;
    }

    public function decide(Tokenable $token, iterable $attributes, object $subject = null): bool
    {
        $grant = 0;

        foreach ($attributes as $attribute) {
            foreach ($this->voters as $voter) {
                $decision = $voter->vote($token, [$attribute], $subject);

                switch ($decision) {
                    case Votable::ACCESS_GRANTED:
                        ++$grant;
                        break;

                    case Votable::ACCESS_DENIED:
                        return false;

                    default:
                        break;
                }
            }
        }

        return ($grant > 0) ?? $this->allowIfAllAbstain;
    }
}
