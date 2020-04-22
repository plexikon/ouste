<?php

namespace Plexikon\Ouste\Support\Contracts\Domain\User;

use Plexikon\Ouste\Support\Contracts\Http\Value\EncodedCredentials;

interface LocalUser extends User
{
    /**
     * @return EncodedCredentials
     */
    public function getPassword(): EncodedCredentials;
}
