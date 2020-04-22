<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authentication;

use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;

interface Authenticatable
{
    /**
     * @param Tokenable $token
     * @return Tokenable
     * @throws AuthenticationException
     * @throws AuthenticationServiceFailure
     */
    public function authenticateToken(Tokenable $token): Tokenable;
}
