<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Value\User;

use Plexikon\Ouste\Support\Contracts\Http\Value\EmailAddress as EmailValue;
use Plexikon\Ouste\Support\Contracts\Http\Value\EmailIdentifier;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

class UserEmailIdentifier implements EmailIdentifier, EmailValue
{
    private EmailValue $email;

    protected function __construct(EmailValue $email)
    {
        $this->email = $email;
    }

    public static function fromString($email): self
    {
        return new self(EmailAddress::fromString($email));
    }

    public static function fromValue(EmailAddress $email): self
    {
        if ($email instanceof EmailIdentifier) {
            $email = $email->getValue();

            return self::fromString($email);
        }

        return new self(clone $email);
    }

    public function getValue(): string
    {
        return $this->email->getValue();
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->email->sameValueAs($aValue->email);
    }
}
