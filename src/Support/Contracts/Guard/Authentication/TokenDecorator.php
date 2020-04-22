<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authentication;

interface TokenDecorator
{
    /**
     * @param Tokenable $token
     * @return Tokenable
     */
    public function decorate(Tokenable $token): Tokenable;
}
