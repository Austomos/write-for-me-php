<?php

namespace Austomos\WriteForMePhp\Tests\Api\Task;

use Austomos\WriteForMePhp\Api\Task\DetailedTaskSolutions;
use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\Interfaces\ResponseInterface;
use Austomos\WriteForMePhp\UserLogin;
use Austomos\WriteForMePhp\WriteForMe;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class DetailedTaskSolutionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $mockUserLogin = new UserLogin('mock_username', 'mock_password');
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
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDetailedTaskSolutionsSuccess(): void
    {
        $mockHandler = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"body": "mock_value"}'),
        ]);

        $detailedTaskSolutions = new DetailedTaskSolutions();
        $reflection = new ReflectionClass(DetailedTaskSolutions::class);
        $reflectionParent = $reflection->getParentClass();
        try {
            $clientProperty = $reflectionParent->getProperty('guzzleClient');
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }
        $clientProperty->setValue(
            $detailedTaskSolutions,
            new Client([
                'handler' => HandlerStack::create($mockHandler)
            ])
        );

        $detailedTaskSolutions->setTask('mock_task');
        try {
            $response = $detailedTaskSolutions->request();
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertSame(['body' => 'mock_value'], $response->getResponseBodyArray());
        $this->assertEquals(200, $response->response()->getStatusCode());
    }

//    public function testDetailedTaskSolutionsLoginException(): void
//    {
//        $wfm = new WriteForMe();
//        $reflection = new ReflectionClass(WriteForMe::class);
//        $loginProperty = $reflection->getProperty('login');
//        $loginProperty->setValue($wfm, new UserLogin('mock_username', 'mock_password'));
//
//        $this->expectException(RuntimeException::class);
//        $this->expectExceptionMessage(
//            'You must login first, by calling WriteForMe::create() before calling WriteForMe::login()'
//        );
//        $wfm->task()->detailedTaskSolutions();
//    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testDetailedTaskSolutionsInvalidArgumentException(): void
    {
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
