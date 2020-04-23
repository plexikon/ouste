<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization;

use Illuminate\Contracts\Container\Container;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\AuthorizationChecker;

class VoterAware
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function isGranted(array $attributes, object $subject = null)
    {
        return $this->container->get(AuthorizationChecker::class)->isGranted($attributes, $subject);
    }

    public function isNotGranted(array $attributes, object $subject = null)
    {
        return $this->container->get(AuthorizationChecker::class)->isNotGranted($attributes, $subject);
    }

    public function getAuthenticatedUser(): ?User
    {
        $user = $this->getStorage()->getToken()->getUser();

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    public function getToken(): ?Tokenable
    {
        return $this->getStorage()->getToken();
    }

    public function getStorage(): TokenStorage
    {
        return $this->container->get(TokenStorage::class);
    }
}
