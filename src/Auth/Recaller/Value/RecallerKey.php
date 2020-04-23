<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Value;

use Plexikon\Ouste\Exception\Validate;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

final class RecallerKey implements Value
{
    private string $key;

    public function __construct($key)
    {
        Validate::that($key, 'Recaller key is invalid')->notBlank()->string();

        $this->key = $key;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return get_class($aValue) === get_class($this)
            && $this->getValue() === $aValue->getValue();
    }

    public function getValue(): string
    {
        return $this->key;
    }
}
