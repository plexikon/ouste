<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Guard\Authorization\Voter;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\Tokenable;
use Plexikon\Ouste\Support\Contracts\Guard\Authentication\TrustResolver;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\RoleHierarchy;
use Plexikon\Ouste\Support\Contracts\Guard\Authorization\Votable;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionVoter implements Votable
{
    const ALIAS = 'expression_voter.default';

    private ExpressionLanguage $expressionLanguage;
    private TrustResolver $trustResolver;
    private RoleHierarchy $roleHierarchy;

    public function __construct(ExpressionLanguage $expressionLanguage,
                                TrustResolver $trustResolver,
                                RoleHierarchy $roleHierarchy)
    {
        $this->expressionLanguage = $expressionLanguage;
        $this->trustResolver = $trustResolver;
        $this->roleHierarchy = $roleHierarchy;
    }

    public function addExpressionLanguageProvider(ExpressionFunctionProviderInterface $provider)
    {
        $this->expressionLanguage->registerProvider($provider);
    }

    public function vote(Tokenable $token, iterable $attributes, object $subject): int
    {
        $vote = self::ACCESS_ABSTAIN;

        $variables = null;

        foreach ($attributes as $attribute) {
            if (!$this->supportAttribute($attribute)) {
                continue;
            }

            if (!$attribute instanceof Expression) {
                $attribute = new Expression($attribute);
            }

            if (null === $variables) {
                $variables = $this->getVariables($token, $subject);
            }

            $vote = self::ACCESS_DENIED;

            if ($this->expressionLanguage->evaluate($attribute, $variables)) {
                return self::ACCESS_GRANTED;
            }
        }

        return $vote;
    }

    protected function supportAttribute($attribute): bool
    {
        // this allow to use expression in routes but as the disadvantage
        // that any expression without parenthesis could not be evaluated
        // e.g "ROLE_USER in roles" and any logical expressions
        return $attribute instanceof Expression
            || Str::contains($attribute, '(') && Str::contains($attribute, ')');
    }

    protected function getVariables(Tokenable $token, $subject): array
    {
        $variables = [
            'token' => $token,
            'identity' => $token->getUser(),
            'subject' => $subject,
            'roles' => $this->getTokenRoles($token),
            'trust_resolver' => $this->trustResolver
        ];

        if ($subject instanceof Request) {
            $variables['request'] = $subject;
        }

        return $variables;
    }

    protected function getTokenRoles(Tokenable $token): array
    {
        $roles = $token->getRoles();

        if ($this->roleHierarchy) {
            return $this->roleHierarchy->getReachableRoles(...$roles);
        }

        return $roles;
    }
}
