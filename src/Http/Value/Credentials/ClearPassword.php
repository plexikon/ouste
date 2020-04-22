<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Value\Credentials;

use Plexikon\Ouste\Exception\Validate;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

class ClearPassword implements ClearCredentials
{
    const MIN_LENGTH = 8;
    const MAX_LENGTH = 255;

    private string $password;

    public function __construct($password)
    {
        $message = "Password must be between 8 and 255 characters";

        Validate::that($password, $message)
            ->notBlank()
            ->string($password)
            ->betweenLength(self::MIN_LENGTH, self::MAX_LENGTH);

        $this->password = $password;
    }

    public function sameValueAs(Value $aValue): bool
    {
        /** @var ClearPassword $aValue */
        return get_class($this) === get_class($aValue)
            && $this->getValue() === $aValue->getValue();
    }

    public function getValue(): string
    {
        return $this->password;
    }
}
