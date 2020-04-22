<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authentication;

interface AuthenticationProvider extends Authenticatable
{
    /**
     * @param Tokenable $token
     * @return bool
     */
    public function supportToken(Tokenable $token): bool;
}
