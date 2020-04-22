<?php

namespace Plexikon\Ouste\Support\Contracts\Domain\User;

use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

interface User
{
    /**
     * @return Identifier
     */
    public function getIdentifier(): Identifier;

    /**
     * @return array
     */
    public function getRoles(): array;
}
