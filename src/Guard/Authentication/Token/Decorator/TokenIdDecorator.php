<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication\Token\Decorator;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenDecorator;
use Ramsey\Uuid\Uuid;

final class TokenIdDecorator implements TokenDecorator
{
    public function decorate(Tokenable $token): Tokenable
    {
        $token->withHeader(Tokenable::TOKEN_ID, Uuid::uuid4()->toString());

        return $token;
    }
}
