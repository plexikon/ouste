<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Local;

use Illuminate\Http\Request;
use Plexikon\Ouste\Http\Value\Credentials\ClearPassword;
use Plexikon\Ouste\Http\Value\User\UserEmailIdentifier;
use Plexikon\Ouste\Support\Contracts\Http\Request\IdentifierCredentialsRequest;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

final class EmailPasswordRequest implements IdentifierCredentialsRequest
{
    private string $loginRouteName;
    private string $identifierInput;
    private string $passwordInput;

    public function __construct(string $loginRouteName,
                                string $identifierInput = 'identifier',
                                string $passwordInput = 'credentials')
    {
        $this->loginRouteName = $loginRouteName;
        $this->identifierInput = $identifierInput;
        $this->passwordInput = $passwordInput;
    }

    public function match(Request $request): bool
    {
        return $request->route()->getName() === $this->loginRouteName;
    }

    public function extractCredentials(Request $request): array
    {
        return [
            $this->extractIdentifier($request),
            $this->extractPassword($request)
        ];
    }

    public function extractIdentifier(Request $request): Identifier
    {
        return UserEmailIdentifier::fromString(
            $request->input($this->identifierInput)
        );
    }

    public function extractPassword(Request $request): ClearCredentials
    {
        return new ClearPassword(
            $request->input($this->passwordInput)
        );
    }
}
