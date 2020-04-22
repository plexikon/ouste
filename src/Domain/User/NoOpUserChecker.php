<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Domain\User;

use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserChecker;

final class NoOpUserChecker implements UserChecker
{
    public function onPreAuthentication(User $user): void
    {
        //
    }

    public function onPostAuthentication(User $user): void
    {
        //
    }
}
