<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Encoder;

use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerFreeEncoder;

final class NoopRecallerEncoder implements RecallerFreeEncoder
{
    public function encodeRecaller(...$values): string
    {
        return implode(self::COOKIE_DELIMITER, $values);
    }

    public function decodeRecaller($recaller): string
    {
        if (!is_string($recaller)) {
            throw new AuthenticationException("Invalid recaller");
        }

        return $recaller;
    }

    public function verify(string $hash, ...$values): void
    {
        //
    }
}
