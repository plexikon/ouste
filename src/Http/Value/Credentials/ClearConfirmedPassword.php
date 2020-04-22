<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Value\Credentials;

use Plexikon\Ouste\Exception\Validate;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

final class ClearConfirmedPassword extends ClearPassword
{
    public function __construct($password, $passwordConfirmation)
    {
        Validate::that($passwordConfirmation, 'Password confirmation does not match')->same($password);

        parent::__construct($password);
    }

    public function sameValueAs(Value $aValue): bool
    {
        /** @var ClearConfirmedPassword $aValue */
        return get_class($this) === get_class($aValue)
            && $this->getValue() === $aValue->getValue();
    }
}
