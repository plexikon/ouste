<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication\Token;

trait HasTokenHeaders
{
    private array $headers = [];

    public function withHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    public function withHeader(string $header, $value): void
    {
        $this->headers[$header] = $value;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $header, $default = null)
    {
        if ($this->hasHeader($header)) {
            return $this->headers[$header];
        }

        return $default;
    }

    public function hasHeader(string $header): bool
    {
        return array_key_exists($header, $this->headers);
    }
}
