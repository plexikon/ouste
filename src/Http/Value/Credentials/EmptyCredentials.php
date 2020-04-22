<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Value\Credentials;

use Plexikon\Ouste\Exception\RuntimeException;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

final class EmptyCredentials implements ClearCredentials
{
    public function getValue()
    {
        throw new RuntimeException('Empty credentials value should never be called');
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this;
    }
}
