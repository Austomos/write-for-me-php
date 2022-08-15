<?php

namespace Austomos\WriteForMePhp\Tests\Api\Task;

use Austomos\WriteForMePhp\Api\Task\DetailedTaskSolutions;
use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\UserLogin;
use Austomos\WriteForMePhp\WriteForMe;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class DetailedTaskSolutionsTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDetailedTaskSolutionsLoginException(): void
    {
        $this->expectException(WriteForMeException::class);
        $this->expectExceptionMessage(
            'You must login first, by calling WriteForMe::create() before calling WriteForMe::login()'
        );
        $wfm = new WriteForMe();
        $wfm->task()->detailedTaskSolutions();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testDetailedTaskSolutionsInvalidArgumentException(): void
    {
        $mockUserLogin = Mockery::mock(UserLogin::class)->makePartial();
        $mockUserLogin->expects('getToken')->andReturn('mock_token');

        $mockWriteForMe = Mockery::mock('overload:' . WriteForMe::class)->makePartial();
        $mockWriteForMe->expects('login')->andReturn($mockUserLogin);


        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid task id');
        $detailedTaskSolutions = new DetailedTaskSolutions();
        try {
            $detailedTaskSolutions->requestResponse();
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }
    }

}
