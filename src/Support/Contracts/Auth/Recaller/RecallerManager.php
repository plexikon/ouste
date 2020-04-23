<?php

namespace Plexikon\Ouste\Support\Contracts\Auth\Recaller;

use Illuminate\Http\Request;

interface RecallerManager
{
    /**
     * Extract cookie
     *
     * @param Request $request
     * @return Recaller|null
     */
    public function extract(Request $request): ?Recaller;

    /**
     * Queue cookie
     *
     * @param mixed ...$values
     */
    public function queue(...$values): void;

    /**
     * Forget Cookie
     *
     * @param Request $request
     */
    public function forget(Request $request): void;

    /**
     * Return cookie name
     *
     * @return string
     */
    public function cookieName(): string;
}
