<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authorization;

use Illuminate\Http\Request;

interface AuthorizationChecker
{
    /**
     * Check if a token, identity is granted to access resources
     *
     * @param iterable $attributes
     * @param object|null $subject
     * @return bool
     */
    public function isGranted(iterable $attributes, ?object $subject): bool;

    /**
     * Check if a token, identity is not granted to access resources
     *
     * @param iterable $attributes
     * @param object|null $subject
     * @return bool
     */
    public function isNotGranted(iterable $attributes, ?object $subject): bool;
}
