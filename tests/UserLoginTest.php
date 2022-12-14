<?php

namespace Austomos\WriteForMePhp\Tests;

use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\UserLogin;
use Austomos\WriteForMePhp\WriteForMe;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class UserLoginTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testLoginInvalidArgumentExceptionNoUser(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Username cannot be empty');
        new UserLogin('', 'mock_password');
    }

    public function testLoginInvalidArgumentExceptionNoPassword(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password cannot be empty');
        new UserLogin('mock_username', '');
    }

    public function testLoginHttpResultException(): void
    {
        $mockHandler = new MockHandler([
            new RequestException(
                'Unauthorized mock',
                new Request('GET', '/getSolutions'),
                new Response(401, reason: 'Unauthorized mock')
            )
        ]);

        $mockClient = new Client([
            'handler' => HandlerStack::create($mockHandler)
        ]);

        $userLogin = new UserLogin('mock_username', 'mock_password');

        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setValue(
            $userLogin,
            $mockClient
        );

        $this->expectException(WriteForMeException::class);
        $this->expectExceptionMessage('Unauthorized mock');
        $this->expectExceptionCode(401);
        try {
            $userLogin->login();
        } catch (InvalidArgumentException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testLoginSuccessReturnFalseException(): void
    {
        $mockHandler = new MockHandler([
            new Response(
                200,
                [],
                '{"success": false, "message": "failed"}'
            ),
        ]);

        $mockClient = new Client([
            'handler' => HandlerStack::create($mockHandler)
        ]);

        $userLogin = new UserLogin('mock_username', 'mock_password');

        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setValue(
            $userLogin,
            $mockClient
        );

        $this->expectException(WriteForMeException::class);
        $this->expectExceptionMessage('Login failed: failed');
        try {
            $userLogin->login();
        } catch (InvalidArgumentException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testLoginSuccessReturnJsonException(): void
    {
        $mockHandler = new MockHandler([
            new Response(
                200,
                [],
                'mock_string_json_failed'
            ),
        ]);

        $mockClient = new Client([
            'handler' => HandlerStack::create($mockHandler)
        ]);

        $userLogin = new UserLogin('mock_username', 'mock_password');

        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('client');

        $clientProperty->setValue(
            $userLogin,
            $mockClient
        );

        $this->expectException(WriteForMeException::class);
        $this->expectExceptionMessage('Syntax error');
        try {
            $userLogin->login();
        } catch (InvalidArgumentException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testLoginSuccess(): void
    {
        $mockHandler = new MockHandler([
            new Response(
                200,
                [],
                '{"success": true, "token": "mock_token", "username": "mock_username", "id": "mock_id"}'
            ),
        ]);

        $mockClient = new Client([
            'handler' => HandlerStack::create($mockHandler)
        ]);

        $userLogin = new UserLogin('mock_username', 'mock_password');

        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setValue(
            $userLogin,
            $mockClient
        );

        try {
            $userLogin->login();
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertSame('mock_username', $userLogin->getUsername());
        $this->assertSame('mock_id', $userLogin->getUserId());
        $this->assertSame('mock_token', $userLogin->getToken());
        $this->assertTrue($userLogin->isConnected());
        $this->assertIsArray($userLogin->getLogin());
    }
}
