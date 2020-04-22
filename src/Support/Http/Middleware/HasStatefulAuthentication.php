<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Support\Http\Middleware;

trait HasStatefulAuthentication
{
    protected $recallerService;

    public function setRecallerService($recallerService): void
    {
        $this->recallerService = $recallerService;
    }
}
