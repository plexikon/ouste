<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Middleware;

interface StatefulAuthentication extends Authentication
{
    public function setRecallerService($recallerService): void;
}
