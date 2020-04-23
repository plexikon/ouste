<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Support\Contracts\Auth\Recaller;

use Plexikon\Ouste\Exception\AuthenticationException;

interface RecallerEncoder
{
    public const COOKIE_DELIMITER = '|';

    /**
     * Encode recaller
     *
     * @param string|int ...$values
     * @return string
     */
    public function encodeRecaller(...$values): string;

    /**
     * Decode recaller
     *
     * @param string $recaller
     * @return string
     * @throws AuthenticationException
     */
    public function decodeRecaller($recaller): string;

    /**
     * Check cookie hash
     *
     * @param string $hash
     * @param string|int ...$values
     * @throws AuthenticationException
     */
    public function verify(string $hash, ...$values): void;
}
