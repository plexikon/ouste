<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface Authentication
{
    /**
     * Authentication middleware
     *
     * @param Request $request
     * @param Closure $next
     * @return Closure|Response
     */
    public function authenticate(Request $request, Closure $next);
}
