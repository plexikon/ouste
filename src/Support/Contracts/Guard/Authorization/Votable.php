<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authorization;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

interface Votable
{
    const ACCESS_GRANTED = 1;
    const ACCESS_ABSTAIN = 0;
    const ACCESS_DENIED = -1;

    /**
     * Return the vote of following constants
     * ACCESS_GRANTED, ACCESS_ABSTAIN or ACCESS_DENIED
     *
     * @param Tokenable $token
     * @param iterable $attributes
     * @param object $subject
     * @return int
     */
    public function vote(Tokenable $token, iterable $attributes, object $subject): int;
}
