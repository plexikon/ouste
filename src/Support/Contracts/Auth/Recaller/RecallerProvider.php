<?php

namespace Plexikon\Ouste\Support\Contracts\Auth\Recaller;

use Plexikon\Ouste\Support\Contracts\Domain\User\User;

interface RecallerProvider
{
    /**
     * @param RecallerIdentifier $identifier
     * @return User
     */
    public function identityOfRecaller(RecallerIdentifier $identifier): User;

    /**
     * @param User $currentIdentity
     * @param RecallerIdentifier $newIdentifier
     * @return User
     */
    public function refreshIdentityRecaller(User $currentIdentity,
                                            RecallerIdentifier $newIdentifier): User;
}
