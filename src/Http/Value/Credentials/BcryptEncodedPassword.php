<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Value\Credentials;

use Plexikon\Ouste\Exception\Validate;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\EncodedCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

final class BcryptEncodedPassword implements EncodedCredentials
{
    const ALGORITHM = PASSWORD_BCRYPT;

    private string $encodedPassword;

    protected function __construct(string $encodedPassword)
    {
        $this->encodedPassword = $encodedPassword;
    }

    public static function fromClearConfirmedPassword(ClearConfirmedPassword $password): self
    {
        $encodedPassword = password_hash($password->getValue(), self::ALGORITHM);

        return new self($encodedPassword);
    }

    public function verify(ClearCredentials $password): bool
    {
        return password_verify($password->getValue(), $this->encodedPassword);
    }

    public static function fromString(string $encodedPassword): self
    {
        $hashed = password_get_info($encodedPassword);
        Validate::that(self::ALGORITHM)->same($hashed['algo'] ?? null, "Password is invalid");

        Validate::that($encodedPassword)->length(60);

        return new self($encodedPassword);
    }

    public function getValue(): string
    {
        return $this->encodedPassword;
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->getValue() === $aValue->getValue();
    }
}
