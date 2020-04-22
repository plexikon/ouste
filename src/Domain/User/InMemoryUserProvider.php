<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\User;

use Illuminate\Support\Collection;
use Plexikon\Ouste\Domain\User\Exception\UserNotFound;
use Plexikon\Ouste\Support\Contracts\Domain\User\LocalUser;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserProvider;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

class InMemoryUserProvider implements UserProvider
{
    private Collection $users;

    public function __construct(LocalUser ...$users)
    {
        $this->users = new Collection($users);
    }

    public function userOf(Identifier $identifier): User
    {
        $user = $this->users->first(
            fn(LocalUser $user): bool => $identifier->sameValueAs($user->getIdentifier())
        );

        if (!$user) {
            throw UserNotFound::withIdentifier($identifier);
        }

        return $user;
    }

    public function supports(User $user): bool
    {
        return $user instanceof InMemoryUser;
    }
}
