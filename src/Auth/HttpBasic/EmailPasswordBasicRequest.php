<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\HttpBasic;

use Illuminate\Http\Request;
use Plexikon\Ouste\Http\Value\Credentials\ClearPassword;
use Plexikon\Ouste\Http\Value\User\UserEmailIdentifier;
use Plexikon\Ouste\Support\Contracts\Http\Request\IdentifierCredentialsRequest;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

final class EmailPasswordBasicRequest implements IdentifierCredentialsRequest
{
    public function extractIdentifier(Request $request): Identifier
    {
        return UserEmailIdentifier::fromString($request->getUser());
    }

    public function extractPassword(Request $request): ClearCredentials
    {
        return new ClearPassword($request->getPassword());
    }

    public function extractCredentials(Request $request): array
    {
        return [
            $this->extractIdentifier($request),
            $this->extractPassword($request)
        ];
    }

    public function match(Request $request): bool
    {
        return true;
    }
}
