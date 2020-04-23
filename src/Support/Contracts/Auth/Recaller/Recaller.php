<?php

namespace Plexikon\Ouste\Support\Contracts\Auth\Recaller;

interface Recaller
{
    /**
     * @return string
     */
    public function id(): string;

    /**
     * @return RecallerIdentifier
     */
    public function token(): RecallerIdentifier;

    /**
     * @return int
     */
    public function expires(): int;

    /**
     * @return string
     */
    public function hash(): string;

    /**
     * @return bool
     */
    public function valid(): bool;
}
