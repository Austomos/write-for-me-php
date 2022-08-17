<?php

namespace Austomos\WriteForMePhp\Tests\Api;

use Austomos\WriteForMePhp\Api\Task;
use Austomos\WriteForMePhp\WriteForMe;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testDetailedTaskSolutionsReturned(): void
    {
        $this->assertInstanceOf(
            Task\DetailedTaskSolutions::class,
            (new WriteForMe())->task()->detailedTaskSolutions()
        );
    }

}
