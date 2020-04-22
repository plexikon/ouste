<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication\Token\Decorator;

use DateTimeImmutable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenDecorator;

final class TokenTimestampsDecorator implements TokenDecorator
{
    public function decorate(Tokenable $token): Tokenable
    {
        $datetime = $this->formatDateTime($this->createDateTime());

        !$token->hasHeader(Tokenable::TOKEN_CREATED_AT)
            ? $token->withHeader(Tokenable::TOKEN_CREATED_AT, $datetime)
            : $token->withHeader(Tokenable::TOKEN_UPDATED_AT, $datetime);

        return $token;
    }

    protected function createDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    protected function formatDateTime(DateTimeImmutable $dateTime): string
    {
        return $dateTime->format('Y-m-d\TH:i:s.u');
    }
}
