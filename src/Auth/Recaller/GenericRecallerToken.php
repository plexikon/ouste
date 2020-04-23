<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller;

use Plexikon\Ouste\Guard\Authentication\Token\HasToken;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\RecallerToken;

final class GenericRecallerToken implements RecallerToken
{
    use HasToken;
}
