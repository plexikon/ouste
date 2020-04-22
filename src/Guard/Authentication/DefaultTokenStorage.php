<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;

final class DefaultTokenStorage implements TokenStorage
{
    private ?Tokenable $token = null;

    public function getToken(): ?Tokenable
    {
        return $this->token;
    }

    public function store(?Tokenable $token): void
    {
        $this->token = $token;
    }

    public function clear(): void
    {
        $this->token = null;
    }

    public function isEmpty(): bool
    {
        return !$this->token instanceof Tokenable;
    }

    public function isNotEmpty(): bool
    {
        return $this->token instanceof Tokenable;
    }
}
