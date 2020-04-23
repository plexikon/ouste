<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization\Voter;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\Votable;

abstract class Voter implements Votable
{
    public function vote(Tokenable $token, iterable $attributes, object $subject): int
    {
        $vote = self::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supports($attribute, $subject)) {
                continue;
            }

            $vote = self::ACCESS_DENIED;

            if ($this->voteOn($attribute, $token, $subject)) {
                return self::ACCESS_GRANTED;
            }
        }

        return $vote;
    }

    abstract protected function supports(string $attribute, object $subject): bool;

    abstract protected function voteOn(string $attribute, Tokenable $token, object $subject): bool;
}
