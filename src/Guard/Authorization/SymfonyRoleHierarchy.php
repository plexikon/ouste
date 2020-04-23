<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization;

use Plexikon\Ouste\Support\Contracts\Guard\Authorization\RoleHierarchy;

final class SymfonyRoleHierarchy implements RoleHierarchy
{
    private array $map;
    private array $rolesHierarchy;

    public function __construct(array $rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
        $this->buildRoleMap();
    }

    protected function buildRoleMap(): void
    {
        $this->map = [];

        foreach ($this->rolesHierarchy as $main => $roles) {
            $this->map[$main] = $roles;
            $visited = [];
            $additionalRoles = $roles;

            while ($role = array_shift($additionalRoles)) {
                if (!array_key_exists($role, $this->rolesHierarchy)) {
                    continue;
                }

                $visited[] = $role;

                $this->map[$main] = array_unique(array_merge($this->map[$main], $this->rolesHierarchy[$role]));

                $additionalRoles = array_merge($additionalRoles, array_diff($this->rolesHierarchy[$role], $visited));
            }
        }
    }

    public function getReachableRoles(string ...$roles): array
    {
        $reachableRoles = $roles;

        foreach ($roles as $role) {
            if (!array_key_exists($role, $this->map)) {
                continue;
            }

            foreach ((array)$this->map[$role] as $r) {
                $reachableRoles[] = $r;
            }
        }

        return $reachableRoles;
    }
}
