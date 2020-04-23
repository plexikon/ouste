<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization\Voter;

use Illuminate\Support\Str;
use Plexikon\Ouste\Domain\Role\RoleValue;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\Votable;

class RoleVoter implements Votable
{
    const ROLE_PREFIX = RoleValue::PREFIX;

    public function vote(Tokenable $token, iterable $attributes, object $subject): int
    {
        $vote = self::ACCESS_ABSTAIN;

        $roles = $this->extractRoles($token);

        foreach ($attributes as $attribute) {
            if (!Str::startsWith($attribute, self::ROLE_PREFIX)) {
                continue;
            }

            $vote = self::ACCESS_DENIED;

            foreach ($roles as $role) {
                if ($attribute === $role) {
                    return self::ACCESS_GRANTED;
                }
            }
        }

        return $vote;
    }

    protected function extractRoles(Tokenable $token): array
    {
        return $token->getRoles();
    }
}
