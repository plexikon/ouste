<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authorization;

interface RoleHierarchy
{
    /**
     * @param string ...$roles
     * @return string[]
     */
    public function getReachableRoles(string ...$roles): array;
}
