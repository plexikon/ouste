<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Value;

interface UserIdentifier extends Identifier
{
    /**
     * @return string
     */
    public function getValue(): string;
}
