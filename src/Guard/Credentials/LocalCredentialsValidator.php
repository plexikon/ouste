<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Credentials;

use Plexikon\Ouste\Auth\Local\GenericLocalToken;
use Plexikon\Ouste\Domain\User\Exception\BadCredentials;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Http\Value\Credentials\EmptyCredentials;
use Plexikon\Ouste\Support\Contracts\Domain\User\LocalUser;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\CredentialsChecker;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\EncodedCredentials;

abstract class LocalCredentialsValidator implements CredentialsChecker
{
    public function checkCredentials(User $user, Tokenable $token): void
    {
        if (!$user instanceof LocalUser) {
            throw new AuthenticationServiceFailure("Invalid user given");
        }

        if (!$token instanceof GenericLocalToken) {
            throw new AuthenticationServiceFailure("Invalid token given");
        }

        if ($token->getUser() instanceof LocalUser)
        {
            if (!$token->getUser()->getPassword()->sameValueAs($user->getPassword())) {
                throw BadCredentials::hasChanged();
            }

            return;
        }

        $tokenCredentials = $token->getCredentials();

        if ($tokenCredentials instanceof EmptyCredentials) {
            throw BadCredentials::emptyCredentials();
        }

        if(!$tokenCredentials instanceof ClearCredentials){
            throw new AuthenticationServiceFailure("Invalid credentials type");
        }

        $this->validatePassword($user->getPassword(), $tokenCredentials);
    }

    /**
     * @param EncodedCredentials $encodedCredentials
     * @param ClearCredentials $clearCredentials
     * @throws BadCredentials
     */
    abstract protected function validatePassword(EncodedCredentials $encodedCredentials,
                                                 ClearCredentials $clearCredentials): void;
}
