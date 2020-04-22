<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Anonymous;

use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

final class AnonymousIdentifier implements Identifier
{
    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->getValue() === $aValue->getValue();
    }

    public function getValue()
    {
        return '.anon';
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
