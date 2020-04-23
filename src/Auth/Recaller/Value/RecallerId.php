<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Value;

use Plexikon\Ouste\Exception\Validate;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerIdentifier;
use Plexikon\Ouste\Support\Contracts\Http\Value\Value;

final class RecallerId implements RecallerIdentifier
{
    public const LENGTH = 64;

    private string $identifier;

    protected function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public static function nextIdentity(): self
    {
        return new self(base64_encode(random_bytes(self::LENGTH)));
    }

    public static function fromString($identifier): self
    {
        Validate::that($identifier, 'Recaller identifier is invalid')
            ->notBlank()
            ->string()
            ->base64();

        return new self($identifier);
    }

    public function sameValueAs(Value $aValue): bool
    {
        return $aValue instanceof $this && $this->getValue() === $aValue->getValue();
    }

    public function getValue(): string
    {
        return $this->identifier;
    }
}
