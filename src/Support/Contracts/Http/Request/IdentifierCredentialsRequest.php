<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Request;

use Illuminate\Http\Request;
use Plexikon\Ouste\Support\Contracts\Http\Value\ClearCredentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

interface IdentifierCredentialsRequest extends AuthenticationRequest
{
    /**
     * @param Request $request
     * @return Identifier
     */
    public function extractIdentifier(Request $request): Identifier;

    /**
     * @param Request $request
     * @return ClearCredentials
     */
    public function extractPassword(Request $request): ClearCredentials;
}
