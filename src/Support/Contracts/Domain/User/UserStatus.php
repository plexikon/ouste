<?php

namespace Plexikon\Ouste\Support\Contracts\Domain\User;

interface UserStatus
{
    /**
     * @return bool
     */
    public function isCredentialsExpired(): bool;

    /**
     * @return bool
     */
    public function isUserNonLocked(): bool;

    /**
     * @return bool
     */
    public function isUserEnabled(): bool;
}
