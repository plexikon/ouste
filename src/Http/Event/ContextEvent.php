<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Event;

class ContextEvent
{
    public const PREFIX_SESSION = '__security_';

    private string $context;

    public function __construct(string $context)
    {
        $this->context = $context;
    }

    public function getName(): string
    {
        return $this->context;
    }

    public function sessionName(): string
    {
        return self::PREFIX_SESSION . $this->getName();
    }

    public function __toString(): string
    {
        return $this->sessionName();
    }
}
