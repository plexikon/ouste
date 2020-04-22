<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\User;

use Plexikon\Ouste\Http\Value\Credentials\BcryptEncodedPassword;
use Plexikon\Ouste\Support\Contracts\Domain\User\LocalUser;
use Plexikon\Ouste\Support\Contracts\Http\Value\EmailIdentifier;
use Plexikon\Ouste\Support\Contracts\Http\Value\EncodedCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

class InMemoryUser implements LocalUser
{
    private EmailIdentifier $emailIdentifier;
    private BcryptEncodedPassword $password;
    private array $roles;

    public function __construct(EmailIdentifier $emailIdentifier,
                                BcryptEncodedPassword $password,
                                string ...$roles)
    {
        $this->emailIdentifier = $emailIdentifier;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getPassword(): EncodedCredentials
    {
        return $this->password;
    }

    public function getIdentifier(): Identifier
    {
        return $this->emailIdentifier;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
