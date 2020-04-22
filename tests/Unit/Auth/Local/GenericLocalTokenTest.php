<?php
declare(strict_types=1);

namespace Plexikon\Ouste\Test\Unit\Auth\Local;

use Generator;
use Plexikon\Ouste\Auth\Local\GenericLocalToken;
use Plexikon\Ouste\Exception\AuthenticationServiceFailure;
use Plexikon\Ouste\Http\Value\Credentials\ClearPassword;
use Plexikon\Ouste\Http\Value\User\UserEmailIdentifier;
use Plexikon\Ouste\Test\Unit\TestCase;

class GenericLocalTokenTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_constructed(): void
    {
        $token = new GenericLocalToken($roles = ['role']);
        $token->withContext('front_end');
        $token->withUser(UserEmailIdentifier::fromString('foo@bar.com'));
        $token->withCredentials(new ClearPassword('password1'));

        $this->assertEquals(['role'], $token->getRoles());
        $this->assertEquals('front_end', $token->getContext());
        $this->assertEquals('foo@bar.com', $token->getUser()->getValue());
        $this->assertEquals('password1', $token->getCredentials()->getValue());
    }

    /**
     * @test
     */
    public function it_can_add_headers(): void
    {
        $token = new GenericLocalToken();

        $this->assertEmpty($token->headers());

        $token->withHeaders(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $token->headers());

        $token->withHeader('baz', 'foo_bar');
        $this->assertEquals(['foo' => 'bar', 'baz' => 'foo_bar'], $token->headers());

        $this->assertTrue($token->hasHeader('foo'));
        $this->assertFalse($token->hasHeader('invalid'));
    }

    /**
     * @test
     */
    public function it_set_authenticated_if_token_has_roles(): void
    {
        $token = new GenericLocalToken();

        $this->assertFalse($token->isAuthenticated());

        $authToken = new GenericLocalToken(['role']);

        $this->assertTrue($authToken->isAuthenticated());
    }

    /**
     * @test
     * @dataProvider provideInvalidUserType
     * @param $invalidUser
     */
    public function it_raise_exception_if_user_is_invalid_type($invalidUser): void
    {
        $this->expectException(AuthenticationServiceFailure::class);
        $this->expectErrorMessage('User token must be an identifier or implement user contract');

        $token = new GenericLocalToken();
        $token->withUser($invalidUser);
    }

    /**
     * @test
     */
    public function it_can_be_serialize(): void
    {
        $token = new GenericLocalToken($roles = ['role']);
        $token->withContext('front_end');
        $token->withUser(UserEmailIdentifier::fromString('foo@bar.com'));
        $token->withCredentials(new ClearPassword('password1'));

        dd($token->toJson());
    }

    /**
     * @private
     * @return Generator
     */
    public function provideInvalidUserType(): Generator
    {
        yield [1];
        yield ['foo'];
        yield [new \stdClass()];
    }
}
