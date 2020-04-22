<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication;

use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TrustResolver;

final class GenericTrustResolver implements TrustResolver
{
    private string $anonymousClass;
    private string $rememberedClass;

    public function __construct(string $anonymousClass, string $rememberedClass)
    {
        $this->anonymousClass = $anonymousClass;
        $this->rememberedClass = $rememberedClass;
    }

    public function isFullyAuthenticated(?Tokenable $token): bool
    {
        if (!$token) {
            return false;
        }

        return !$this->isAnonymous($token) && !$this->isRemembered($token);
    }

    public function isRemembered(?Tokenable $token): bool
    {
        return $token instanceof $this->rememberedClass;
    }

    public function isAnonymous(?Tokenable $token): bool
    {
        return $token instanceof $this->anonymousClass;
    }
}
