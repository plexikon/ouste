<?php
declare(strict_types=1);

use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\AuthorizationChecker;

if (!function_exists('getToken')) {
    /**
     * Return token if exists from storage
     *
     * @return Tokenable|null
     */
    function getToken(): ?Tokenable
    {
        return app(TokenStorage::class)->getToken();
    }
}

if (!function_exists('getIdentity')) {
    /**
     * Return user from token if not anonymous
     *
     * @return User|null
     */
    function getIdentity(): ?User
    {
        $user = getToken()->getUser();

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}

if (!function_exists('isGranted')) {
    /**
     * Grant token, identity to access resource
     *
     * @param string|array $attributes
     * @param object|null $subject
     * @return bool
     */
    function isGranted($attributes, object $subject = null): bool
    {
        return app(AuthorizationChecker::class)->isGranted((array)$attributes, $subject);
    }
}

if (!function_exists('isNotGranted')) {
    /**
     * Deny access to token, identity to access resource
     *
     * @param string $attribute
     * @param object|null $subject
     * @return bool
     */
    function isNotGranted(string $attribute, object $subject = null): bool
    {
        return !isGranted($attribute, $subject);
    }
}
