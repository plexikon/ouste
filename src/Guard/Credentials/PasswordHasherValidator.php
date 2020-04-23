<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Credentials;

use Illuminate\Contracts\Hashing\Hasher;
use Plexikon\Ouste\Domain\User\Exception\BadCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\EncodedCredentials;

final class PasswordHasherValidator extends LocalCredentialsValidator
{
    private Hasher $hasher;
    private array $hasherOptions;

    public function __construct(Hasher $hasher, array $hasherOptions = [])
    {
        $this->hasher = $hasher;
        $this->hasherOptions = $hasherOptions;
    }

    protected function validatePassword(EncodedCredentials $encodedCredentials, ClearCredentials $clearCredentials): void
    {
        $verify = $this->hasher->check(
            $clearCredentials->getValue(),
            $encodedCredentials->getValue(),
            $this->hasherOptions
        );

        if(!$verify){
            throw BadCredentials::invalid();
        }
    }
}
