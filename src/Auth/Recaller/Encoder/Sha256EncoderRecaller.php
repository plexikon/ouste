<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Encoder;

use Plexikon\Ouste\Auth\Recaller\Value\RecallerKey;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerEncoder;

final class Sha256EncoderRecaller implements RecallerEncoder
{
    private RecallerKey $recallerKey;

    public function __construct(RecallerKey $recallerKey)
    {
        $this->recallerKey = $recallerKey;
    }

    public function encodeRecaller(...$values): string
    {
        $hash = $this->generateCookieHash(...$values);

        return base64_encode(implode(self::COOKIE_DELIMITER, [...$values, $hash]));
    }

    public function decodeRecaller($recaller): string
    {
        $recaller = base64_decode($recaller);

        if (!is_string($recaller)) {
            throw new AuthenticationException("invalid recaller");
        }

        return $recaller;
    }

    public function verify(string $hash, ...$values): void
    {
        if (!hash_equals($hash, $this->generateCookieHash(...$values))) {
            throw new AuthenticationException("Invalid recaller");
        }
    }

    /**
     * @param mixed ...$values
     * @return string
     */
    protected function generateCookieHash(...$values): string
    {
        $key = $this->recallerKey->getValue();

        return hash_hmac('sha256', implode(self::COOKIE_DELIMITER, $values), $key);
    }
}
