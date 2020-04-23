<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization\Voter;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\RoleHierarchy;

class RoleHierarchyVoter extends RoleVoter
{
    private RoleHierarchy $roleHierarchy;

    public function __construct(RoleHierarchy $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    protected function extractRoles(Tokenable $token): array
    {
        return $this->roleHierarchy->getReachableRoles(...$token->getRoles());
    }
}
