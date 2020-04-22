<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Http\Middleware;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Plexikon\Ouste\Exception\RuntimeException;
use Plexikon\Ouste\Http\Event\ContextEvent;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TokenStorage;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TrustResolver;
use Symfony\Component\HttpFoundation\Response;

class ContextEventHandler
{
    private ?ContextEvent $contextEvent = null;
    private TokenStorage $tokenStorage;
    private TrustResolver $trustResolver;
    private Dispatcher $dispatcher;
    private bool $allowManyContext;

    public function __construct(TokenStorage $tokenStorage,
                                TrustResolver $trustResolver,
                                Dispatcher $dispatcher,
                                bool $allowManyContext = false)
    {
        $this->tokenStorage = $tokenStorage;
        $this->trustResolver = $trustResolver;
        $this->dispatcher = $dispatcher;
        $this->allowManyContext = $allowManyContext;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->dispatcher->listen(ContextEvent::class, [$this, 'onContextEvent']);

        $response = $next($request);

        return $this->terminateResponse($request, $response);
    }

    protected function terminateResponse(Request $request, Response $response): Response
    {
        if ($this->contextEvent) {
            $token = $this->tokenStorage->getToken();

            if (!$token || $this->trustResolver->isAnonymous($token)) {
                $request->session()->forget($this->contextEvent->sessionName());
            } else {
                $request->session()->put($this->contextEvent->sessionName(), serialize($token));
            }
        }

        return $response;
    }

    public function onContextEvent(ContextEvent $contextEvent): void
    {
        if ($this->contextEvent && !$this->allowManyContext) {
            throw new RuntimeException("Context event can run only once per request");
        }

        $this->contextEvent = $contextEvent;
    }
}
