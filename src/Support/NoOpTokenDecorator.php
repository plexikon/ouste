<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Support;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenDecorator;

final class NoOpTokenDecorator implements TokenDecorator
{
    public function decorate(Tokenable $token): Tokenable
    {
       return $token;
    }
}
