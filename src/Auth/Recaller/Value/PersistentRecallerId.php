<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Value;

use Illuminate\Support\Str;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\Recaller;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerEncoder;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerIdentifier;

final class PersistentRecallerId implements Recaller
{
    protected string $recaller;

    public function __construct(string $recaller)
    {
        $this->recaller = $recaller;
    }

    public function id(): string
    {
        return $this->recallerPartAt(0);
    }

    public function token(): RecallerIdentifier
    {
        return RecallerId::fromString($this->recallerPartAt(1));
    }

    public function expires(): int
    {
        return (int)$this->recallerPartAt(2);
    }

    public function hash(): string
    {
        return $this->recallerPartAt(3);
    }

    public function valid(): bool
    {
        return $this->properString() && $this->hasAllSegments();
    }

    protected function properString(): bool
    {
        return is_string($this->recaller)
            && Str::contains($this->recaller, RecallerEncoder::COOKIE_DELIMITER);
    }

    protected function hasAllSegments(): bool
    {
        $segments = explode(RecallerEncoder::COOKIE_DELIMITER, $this->recaller);

        return count($segments) === 4 && trim($segments[0]) !== '' && trim($segments[1]) !== '';
    }

    /**
     * Return recaller part
     *
     * @param int $position
     * @return mixed
     */
    protected function recallerPartAt(int $position)
    {
        return explode(RecallerEncoder::COOKIE_DELIMITER, $this->recaller, 4)[$position];
    }
}
