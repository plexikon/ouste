<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Local;

use Plexikon\Ouste\Guard\Authentication\Token\HasToken;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\LocalToken;

class GenericLocalToken implements LocalToken
{
    use HasToken;
}
