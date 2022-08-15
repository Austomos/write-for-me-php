<?php

namespace Austomos\WriteForMePhp\Tests;

use Austomos\WriteForMePhp\Api\Task;
use Austomos\WriteForMePhp\WriteForMe;
use PHPUnit\Framework\TestCase;

class WriteForMeTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
    public function testLoginCreateNotCalledRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
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
}
