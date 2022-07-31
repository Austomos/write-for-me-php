<?php

namespace Austomos\WriteForMePhp\Tests;

use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\UserLogin;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserLoginTest extends TestCase
{

    public function testLoginInvalidArgumentExceptionNoUserNoPassword(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Username and password are required');
        $userLogin = new UserLogin();
        $stub = Mockery::mock(Client::class);
        $userLogin->login($stub, '', '');
    }

    public function testLoginInvalidArgumentExceptionNoUser(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Username and password are required');
        $userLogin = new UserLogin();
        $stub = Mockery::mock(Client::class);
        $userLogin->login($stub, '', 'mock_password');
    }

    public function testLoginInvalidArgumentExceptionNoPassword(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Username and password are required');
        $userLogin = new UserLogin();
        $stub = Mockery::mock(Client::class);
        $userLogin->login($stub, 'mock_user', '');
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

        $this->expectException(WriteForMeException::class);
        $this->expectExceptionMessage('Unauthorized mock');
        $userLogin = new UserLogin();
        try {
            $userLogin->login($mockClient, 'mock_username', 'mock_password');
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

        $this->expectException(WriteForMeException::class);
        $this->expectExceptionMessage('Login failed: failed');
        $userLogin = new UserLogin();
        try {
            $userLogin->login($mockClient, 'mock_username', 'mock_password');
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

        $userLogin = new UserLogin();
        try {
            $userLogin->login($mockClient, 'mock_username', 'mock_password');
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
