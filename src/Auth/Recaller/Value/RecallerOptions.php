<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Value;

class RecallerOptions
{
    private ?string $name;
    private int $lifetime;
    private string $path;
    private ?string $domain;
    private bool $secure;
    private ?string $sameSite;
    private bool $httpOnly;

    public function __construct(?string $name,
                                int $lifetime,
                                string $path,
                                ?string $domain,
                                bool $secure,
                                ?string $sameSite,
                                bool $httpOnly)
    {
        $this->name = $name;
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->sameSite = $sameSite;
        $this->httpOnly = $httpOnly;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function getSameSite(): ?string
    {
        return $this->sameSite;
    }

    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }
}
