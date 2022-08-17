<?php

namespace Austomos\WriteForMePhp\Tests;

use Austomos\WriteForMePhp\Api\Task;
use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\UserLogin;
use Austomos\WriteForMePhp\WriteForMe;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

class WriteForMeTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
    public function testLoginCreateNotSetRuntimeException(): void
    {
        $wfm = new WriteForMe();
        $reflection = new ReflectionClass(WriteForMe::class);
        $loginProperty = $reflection->getProperty('login');
        $loginProperty->setValue($wfm, new UserLogin('mock_username', 'mock_password'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'You must login first, by calling WriteForMe::create() before calling WriteForMe::login()'
        );
        WriteForMe::login();
    }

    public function testCreateSuccess(): void
    {
        $mockUserLogin = new UserLogin('mock_username', 'mock_password');
        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $mockUserLogin,
            ['success' => true, 'token' => 'mock_token']
        );

        WriteForMe::create($mockUserLogin);
        $this->assertInstanceOf(UserLogin::class, $login = WriteForMe::login());
        $this->assertEquals('mock_token', $login->getToken());
    }

    public function testTask(): void
    {
        $wfm = new WriteForMe();
        $this->assertInstanceOf(Task::class, $wfm->task());
    }
}
