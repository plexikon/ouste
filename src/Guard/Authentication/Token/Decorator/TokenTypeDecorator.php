<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication\Token\Decorator;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenDecorator;
use function get_class;

class TokenTypeDecorator implements TokenDecorator
{
    public function decorate(Tokenable $token): Tokenable
    {
        $token->withHeader(Tokenable::TOKEN_TYPE, get_class($token));

        return $token;
    }
}
