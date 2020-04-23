<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Firewall;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Plexikon\Ouste\Auth\Anonymous\AnonymousAuthentication;
use Plexikon\Ouste\Auth\Anonymous\ProvideAnonymousAuthentication;
use Plexikon\Ouste\Auth\Context\ContextAuthentication;
use Plexikon\Ouste\Auth\Local\EmailPasswordRequest;
use Plexikon\Ouste\Auth\Local\LocalAuthentication;
use Plexikon\Ouste\Auth\Local\ProvideLocalAuthentication;
use Plexikon\Ouste\Domain\User\InMemoryUser;
use Plexikon\Ouste\Domain\User\InMemoryUserProvider;
use Plexikon\Ouste\Domain\User\NoOpUserChecker;
use Plexikon\Ouste\Guard\Authentication\AuthenticationManager;
use Plexikon\Ouste\Guard\Credentials\PasswordHasherValidator;
use Plexikon\Ouste\Guard\Guard;
use Plexikon\Ouste\Http\Event\ContextEvent;
use Plexikon\Ouste\Http\Response\HomeAuthenticationResponse;
use Plexikon\Ouste\Http\Response\WebEntrypoint;
use Plexikon\Ouste\Http\Value\Credentials\BcryptEncodedPassword;
use Plexikon\Ouste\Http\Value\User\UserEmailIdentifier;
use Plexikon\Ouste\Support\Contracts\Domain\User\UserProvider;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Authenticatable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Guardable;
use Plexikon\Ouste\Support\NoOpTokenDecorator;

class AuthenticationFirewall
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function handle(Request $request, Closure $next, string $firewall = null)
    {
        // token storage singleton

        $context = $firewall ?? 'front_end';
        $userProvider = new InMemoryUserProvider(...$this->loadUsers());
        $manager = new AuthenticationManager(
            ...$this->authenticationProviders($userProvider, $context)
        );
        $this->container->instance(Authenticatable::class, $manager);

        $middleware = $this->stackMiddleware([$userProvider], $context);
        $guard = $this->newGuardInstance($manager);

        foreach ($middleware as $service) {
            $service->setGuard($guard);
        }

        return (new Pipeline($this->container))
            ->via('authenticate')
            ->send($request)
            ->through($middleware)
            ->then(function () use ($request, $next) {
                return $next($request);
            });
    }

    private function stackMiddleware(array $userProviders, string $context): array
    {
        return [
            new ContextAuthentication(
                new ContextEvent('front_end'),
                ... $userProviders
            ),

            new LocalAuthentication(
                new EmailPasswordRequest('auth.login.post'),
                $this->container->make(HomeAuthenticationResponse::class),
                $context
            ),

            new AnonymousAuthentication($context . '.anon'),
        ];
    }

    private function newGuardInstance(Authenticatable $authManager): Guardable
    {
        return new Guard(
            $this->container->get(TokenStorage::class),
            $authManager,
            $this->container->get(Dispatcher::class),
            $this->container->make(WebEntrypoint::class)
        );
    }

    private function authenticationProviders(UserProvider $userProvider, string $context): array
    {
        return [
            new ProvideLocalAuthentication(
                $userProvider,
                new NoOpUserChecker(),
                new PasswordHasherValidator(
                    $this->container->get(Hasher::class)
                ),
                new NoOpTokenDecorator(),
                $context
            ),

            new ProvideAnonymousAuthentication($context . '.anon'),
        ];
    }

    private function loadUsers(): array
    {
        return [
            new InMemoryUser(
                UserEmailIdentifier::fromString('plexikon@protonmail.com'),
                BcryptEncodedPassword::fromString(
                    password_hash('password1', PASSWORD_BCRYPT)
                ),
                'ROLE_USER', 'ROLE_ADMIN'
            ),

            new InMemoryUser(
                UserEmailIdentifier::fromString('john@gmail.com'),
                BcryptEncodedPassword::fromString(
                    password_hash('password1', PASSWORD_BCRYPT)
                ),
                'ROLE_USER'
            ),
        ];
    }
}
