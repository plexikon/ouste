<?php

namespace Plexikon\Ouste\Support\Contracts\Auth\Recaller;

interface RecallerUser
{
    /**
     * @return RecallerIdentifier
     */
    public function getRecallerIdentifier(): RecallerIdentifier;
}
