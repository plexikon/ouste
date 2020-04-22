<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication\Token;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

class Token implements Tokenable
{
    use HasToken;
}
