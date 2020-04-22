<?php

namespace Plexikon\Ouste\Support\Contracts\Guard\Authentication;

use Plexikon\Ouste\Support\Contracts\Domain\User\User;
use Plexikon\Ouste\Support\Contracts\Http\Value\Credentials;
use Plexikon\Ouste\Support\Contracts\Http\Value\Identifier;

interface Tokenable extends \JsonSerializable
{
    const TOKEN_ID = '__token_id';

    const TOKEN_CONTEXT = '__token_context';

    const TOKEN_TYPE = '__token_type';

    const TOKEN_CREATED_AT = '__token_created_at';

    const TOKEN_UPDATED_AT = '__token_updated_at';

    /**
     * @param array $headers
     */
    public function withHeaders(array $headers): void;

    /**
     * @param string $header
     * @param $value
     */
    public function withHeader(string $header, $value): void;

    /**
     * @param string $header
     * @param null $default
     * @return mixed
     */
    public function header(string $header, $default = null);

    /**
     * @return array
     */
    public function headers(): array;

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader(string $header): bool;

    /**
     * @param $user
     */
    public function withUser($user): void;

    /**
     * @return User|Identifier
     */
    public function getUser();

    /**
     * @return string[]
     */
    public function getRoles(): array;

    /**
     * @param Credentials $credentials
     */
    public function withCredentials(Credentials $credentials): void;

    /**
     * @return Credentials|null
     */
    public function getCredentials(): ?Credentials;

    /**
     * @param string $context
     */
    public function withContext(string $context): void;

    /**
     * @return string
     */
    public function getContext(): string;

    /**
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * @param bool $isAuthenticated
     */
    public function setAuthenticated(bool $isAuthenticated): void;
}
