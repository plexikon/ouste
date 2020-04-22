<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Exception;

use Plexikon\Ouste\Support\Contracts\Exception\OusteException;

class InvalidArgumentException extends \InvalidArgumentException implements OusteException
{
    //
}
