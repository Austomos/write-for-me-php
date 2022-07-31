<?php

namespace Austomos\WriteForMePhp\Tests;

use Austomos\WriteForMePhp\Api\Task;
use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\WriteForMe;
use PHPUnit\Framework\TestCase;

class WriteForMeTest extends TestCase
{

    public function testLoginCreateNotCalledRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You must call create() before calling login()');
        WriteForMe::login();
    }

    public function testTaskReturned(): void
    {
        $wfm = new WriteForMe();
        $this->assertInstanceOf(Task::class, $wfm->task());
    }

    public function testClientWithoutLoginException(): void
    {
        $this->expectException(WriteForMeException::class);
        $this->expectExceptionMessage('You must login first');
        $wfm = new WriteForMe();
        $wfm->task()->detailedTaskSolutions();
    }
}
