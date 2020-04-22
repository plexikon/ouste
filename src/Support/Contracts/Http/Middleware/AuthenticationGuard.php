<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Middleware;

use Plexikon\Ouste\Support\Contracts\Guard\Guardable;

interface AuthenticationGuard extends Authentication
{
    /**
     * Set guard instance on authentication middleware
     *
     * @param Guardable $guard
     */
    public function setGuard(Guardable $guard): void;
}
