<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Anonymous;

use Plexikon\Ouste\Guard\Authentication\Token\HasToken;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;

class GenericAnonymousToken implements Tokenable
{
    use HasToken;
}
