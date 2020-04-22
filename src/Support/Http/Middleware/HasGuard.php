<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Support\Http\Middleware;

use Plexikon\Ouste\Support\Contracts\Guard\Guardable;

trait HasGuard
{
    protected ?Guardable $guard;

    public function setGuard(Guardable $guard): void
    {
        $this->guard = $guard;
    }
}
