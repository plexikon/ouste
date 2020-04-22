<?php

namespace Plexikon\Ouste\Support\Contracts\Domain\User;



use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

interface UserProvider
{
    /**
     * @param Identifier $identifier
     * @return User
     */
    public function userOf(Identifier $identifier): User;

    /**
     * @param User $user
     * @return bool
     */
    public function supports(User $user): bool;
}
