<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authentication;

interface TokenStorage
{
    /**
     * Return storage token if exists
     *
     * @return Tokenable|null
     */
    public function getToken(): ?Tokenable;

    /**
     * Set or clear token storage
     *
     * @param Tokenable|null $token
     */
    public function store(?Tokenable $token): void;

    /**
     * Clear token on storage
     * alias for set token null
     */
    public function clear(): void;

    /**
     * Check token does exists in storage
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Check token exists on storage
     *
     * @return bool
     */
    public function isNotEmpty(): bool;
}
