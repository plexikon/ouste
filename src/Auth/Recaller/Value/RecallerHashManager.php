<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Auth\Recaller\Value;

use Illuminate\Contracts\Cookie\QueueingFactory;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\AuthenticationException;
use Plexikon\Ouste\Exception\RuntimeException;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\Recaller;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerEncoder;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerFreeEncoder;
use Plexikon\Ouste\Support\Contracts\Auth\Recaller\RecallerManager;

final class RecallerHashManager implements RecallerManager
{
    private QueueingFactory $cookie;
    private RecallerOptions $options;
    private RecallerEncoder $encoder;

    public function __construct(QueueingFactory $cookie,
                                RecallerOptions $options,
                                RecallerEncoder $encoder)
    {
        if ($encoder instanceof RecallerFreeEncoder) {
            throw new RuntimeException(
                'Recaller hash manager is not compatible with recaller free encoder'
            );
        }

        $this->cookie = $cookie;
        $this->options = $options;
        $this->encoder = $encoder;
    }

    public function extract(Request $request): ?Recaller
    {
        if (!$recaller = $request->cookie($this->cookieName())) {
            return null;
        }

        $recaller = new PersistentRecallerId($this->encoder->decodeRecaller($recaller));

        $this->assertRecallerIsValid($recaller);

        return $recaller;
    }

    public function queue(...$values): void
    {
        $expires = 0 === $this->options->getLifetime() ? 0 : time() + ($this->options->getLifetime() * 60);

        $values = [...$values, $expires];

        $recallerString = $this->encoder->encodeRecaller(...$values);

        $this->cookie->queue(
            $this->cookie->make(
                $this->cookieName(), $recallerString, $this->options->getLifetime(),
                $this->options->getPath(), $this->options->getDomain(),
                $this->options->isSecure(), $this->options->isHttpOnly(),
                false, $this->options->getSameSite()
            )
        );
    }

    public function forget(Request $request): void
    {
        $this->cookie->queue(
            $this->cookie->forget($this->cookieName())
        );
    }

    public function cookieName(): string
    {
        return $this->options->getName();
    }

    protected function assertRecallerIsValid(Recaller $recaller): void
    {
        if (!$recaller->valid()) {
            throw new AuthenticationException("Authentication failed via recaller");
        }

        $this->encoder->verify(
            $recaller->hash(), $recaller->id(),
            $recaller->token()->getValue(), $recaller->expires()
        );

        if (0 !== $recaller->expires() && $recaller->expires() < time()) {
            throw new AuthenticationException('Cookie has expired');
        }
    }
}
