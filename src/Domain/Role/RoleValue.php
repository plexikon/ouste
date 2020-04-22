<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\Role;

use Plexikon\Ouste\Support\Contracts\Domain\Role\Role;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

class RoleValue implements Role, Value
{
    private string $roleName;

    public function __construct(string $roleName)
    {
        $this->roleName = $roleName;
    }

    public function getRole(): string
    {
        return $this->roleName;
    }

    public function getValue(): string
    {
        return $this->roleName;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->getRole() === $aValue->getRole();
    }
}
