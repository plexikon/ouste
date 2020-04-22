<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication\Token;

use RuntimeException;
use function json_encode;

trait HasTokenSerializer
{
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'user' => $this->getUser(),
            'roles' => $this->getRoles(),
            'is_authenticated' => $this->isAuthenticated(),
            'headers' => $this->headers,
        ];
    }

    public function toJson($options = 0): string
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(json_last_error_msg());
        }

        return $json;
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
