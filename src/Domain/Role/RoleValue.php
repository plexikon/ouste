<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\Role;

use Plexikon\Ouste\Exception\Validate;
use Plexikon\Ouste\Support\Contracts\Domain\Role\Role;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

class RoleValue implements Role, Value
{
    const PREFIX = 'ROLE_';

    private string $role;

    protected function __construct(string $role)
    {
        $this->role = $role;
    }

    public static function fromString(string $roleName): self
    {
        Validate::that($roleName, 'role is invalid')
            ->startsWith($roleName, self::PREFIX)
            ->minLength(8, $roleName);

        return new self($roleName);
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getValue()
    {
        return $this->getRole();
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this
            && $this->getRole() === $aValue->getRole();
    }
}
