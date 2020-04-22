<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authentication\Token;

use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Http\Value\Credentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

trait HasToken
{
    use HasTokenHeaders, HasTokenSerializer;

    private $user;
    private array $roles;
    protected ?Credentials $credentials = null;
    private bool $isAuthenticated = false;

    public function __construct(array $roles = [])
    {
        $this->roles = $roles;

        if (!empty($roles)) {
            $this->setAuthenticated(true);
        }
    }

    public function withUser($user): void
    {
        if (!$user instanceof Identifier && !$user instanceof User) {
            throw new  AuthenticationServiceFailure(
                'User token must be an identifier or implement user contract'
            );
        }

        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getCredentials(): ?Credentials
    {
        return $this->credentials;
    }

    public function withCredentials(Credentials $credentials): void
    {
        $this->credentials = $credentials;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function setAuthenticated(bool $isAuthenticated): void
    {
        $this->isAuthenticated = $isAuthenticated;
    }

    public function withContext(string $context): void
    {
        $this->withHeader(Tokenable::TOKEN_CONTEXT, $context);
    }

    public function getContext(): string
    {
        return $this->header(Tokenable::TOKEN_CONTEXT);
    }
}
