<?php

namespace Plexikon\Ouste\Support\Contracts\Http\Value;

interface EmailAddress extends Value
{
    public function getValue(): string;
}
