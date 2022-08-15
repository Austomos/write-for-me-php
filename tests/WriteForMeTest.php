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
    public function testLoginCreateNotCalledRuntimeException(): void
    {
        new WriteForMe();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'You must login first, by calling WriteForMe::create() before calling WriteForMe::login()'
        );
        WriteForMe::login();
    }

    public function testTaskReturned(): void
    {
        $wfm = new WriteForMe();
        $this->assertInstanceOf(Task::class, $wfm->task());
    }

    public function testCreatSuccess(): void
    {
        $wfm = new WriteForMe();

        $mockUserLogin = Mockery::mock(UserLogin::class)->makePartial();
        $mockUserLogin->expects('login');
        $reflection = new ReflectionClass(WriteForMe::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $wfm,
            $mockUserLogin
        );
        try {
            $this->assertInstanceOf(WriteForMe::class, $wfm::create('mock_username', 'mock_password'));
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }
    }
}
