<?php

namespace Plexikon\Ouste\Support\Contracts\Guard;

use Plexikon\Ouste\Domain\User\Exception\BadCredentials;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

interface CredentialsChecker
{
    /**
     * @param User $user
     * @param Tokenable $token
     * @throws BadCredentials
     */
    public function checkCredentials(User $user, Tokenable $token): void;
}
