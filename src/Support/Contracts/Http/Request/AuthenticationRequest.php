<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Request;

use Illuminate\Http\Request;

interface AuthenticationRequest
{
    /**
     * @param Request $request
     * @return bool
     */
    public function match(Request $request): bool;

    /**
     * @param Request $request
     * @return mixed
     */
    public function extractCredentials(Request $request);
}
