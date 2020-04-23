<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization\Expression;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage as SymfonyExpressionLanguage;

final class ExpressionLanguage extends SymfonyExpressionLanguage
{
    public function __construct(CacheItemPoolInterface $cache = null, array $providers = [])
    {
        array_unshift($providers, new ExpressionProvider());

        parent::__construct($cache, $providers);
    }
}
