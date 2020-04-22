<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Value\User;

use Plexikon\Ouste\Exception\Validate;
use Plexikon\Ouste\Support\Contracts\Http\Value\EmailAddress as EmailValue;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

class EmailAddress implements EmailValue
{
    private string $email;

    protected function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString($email): self
    {
        $message = 'Email address is invalid';

        Validate::that($email, $message)->string()->email();

        return new self($email);
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->getValue() === $aValue->getValue();
    }

    public function getValue(): string
    {
        return $this->email;
    }
}
