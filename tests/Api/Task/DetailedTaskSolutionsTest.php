<?php

namespace Austomos\WriteForMePhp\Tests\Api\Task;

use Austomos\WriteForMePhp\Api\Task\DetailedTaskSolutions;
use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\UserLogin;
use Austomos\WriteForMePhp\WriteForMe;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DetailedTaskSolutionsTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDetailedTaskSolutionsSuccess(): void
    {
        $mockUserLogin = new UserLogin();
        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $mockUserLogin,
            ['token' => 'mock_token', 'success' => true]
        );

        $mockWriteForMe = new WriteForMe();
        $reflection = new ReflectionClass(WriteForMe::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $mockWriteForMe,
            $mockUserLogin
        );

        $detailedTaskSolutions = new DetailedTaskSolutions();
        $detailedTaskSolutions->setTask('mock_task');


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
        $mockUserLogin = new UserLogin();
        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $mockUserLogin,
            ['token' => 'mock_token', 'success' => true]
        );

        $mockWriteForMe = new WriteForMe();
        $reflection = new ReflectionClass(WriteForMe::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $mockWriteForMe,
            $mockUserLogin
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid arguments provided');
        $detailedTaskSolutions = new DetailedTaskSolutions();
        try {
            $detailedTaskSolutions->request();
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testSetterSuccess(): void
    {
        $mockUserLogin = new UserLogin();
        $reflection = new ReflectionClass(UserLogin::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $mockUserLogin,
            ['token' => 'mock_token', 'success' => true]
        );

        $mockWriteForMe = new WriteForMe();
        $reflection = new ReflectionClass(WriteForMe::class);
        $clientProperty = $reflection->getProperty('login');
        $clientProperty->setValue(
            $mockWriteForMe,
            $mockUserLogin
        );

        $detailedTaskSolutions = new DetailedTaskSolutions();
        $detailedTaskSolutions->setLimit(10);
        $detailedTaskSolutions->setQuery('mock_query');
        $detailedTaskSolutions->setSkip(10);
        $detailedTaskSolutions->setSortAsc(true);
        $detailedTaskSolutions->setSortBy('mock_sortBy');
        $detailedTaskSolutions->setTask('mock_task');

        $options = $detailedTaskSolutions->getOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertNotEmpty($json);

        $this->assertArrayHasKey('limit', $json);
        $this->assertEquals(10, $json['limit']);

        $this->assertArrayHasKey('query', $json);
        $this->assertEquals('mock_query', $json['query']);

        $this->assertArrayHasKey('skip', $json);
        $this->assertEquals(10, $json['skip']);

        $this->assertArrayHasKey('sortAsc', $json);
        $this->assertTrue($json['sortAsc']);

        $this->assertArrayHasKey('sortBy', $json);
        $this->assertEquals('mock_sortBy', $json['sortBy']);

        $this->assertArrayHasKey('task', $json);
        $this->assertEquals('mock_task', $json['task']);

        $this->assertArrayHasKey('listType', $json);
        $this->assertEquals('detailledTaskSolutions', $json['listType']);
    }

    public function testGetUriSuccess(): void
    {
        $this->assertEquals('getSolutions', (new DetailedTaskSolutions())->getUri());
    }

    public function testGetMethodSuccess(): void
    {
        $this->assertEquals('POST', (new DetailedTaskSolutions())->getMethod());
    }
}
