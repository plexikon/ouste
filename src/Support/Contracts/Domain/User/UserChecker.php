<?php

namespace Plexikon\Ouste\Support\Contracts\Domain\User;

interface UserChecker
{
    /**
     * @param User $user
     */
    public function onPreAuthentication(User $user): void;

    /**
     * @param User $user
     */
    public function onPostAuthentication(User $user): void;
}
