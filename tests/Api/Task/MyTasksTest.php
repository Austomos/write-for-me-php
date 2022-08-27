<?php

namespace Austomos\WriteForMePhp\Tests\Api\Task;

use Austomos\WriteForMePhp\Api\Task\MyTasks;
use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\UserLogin;
use Austomos\WriteForMePhp\WriteForMe;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class MyTasksTest extends TestCase
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

    public function testMyTasksSuccess(): void
    {
        $mockHandler = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"body": "mock_value"}'),
        ]);

        $myTasks = new MyTasks();
        $reflection = new ReflectionClass(MyTasks::class);
        $reflectionParent = $reflection->getParentClass();
        try {
            $clientProperty = $reflectionParent->getProperty('guzzleClient');
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }
        $clientProperty->setValue(
            $myTasks,
            new Client([
                'handler' => HandlerStack::create($mockHandler)
            ])
        );

        try {
            $response = $myTasks->request();
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertSame(['body' => 'mock_value'], $response->getResponseBodyArray());
        $this->assertEquals(200, $response->response()->getStatusCode());
    }

    public function testSetterSuccess(): void
    {
        $myTasks = new MyTasks();
        $myTasks->setLimit(10);
        $myTasks->setQuery('mock_query');
        $myTasks->setSkip(10);
        $myTasks->setSortAsc(true);
        $myTasks->setSortBy('mock_sortBy');

        $options = $myTasks->getOptions();
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

        $this->assertArrayHasKey('listType', $json);
        $this->assertEquals('myTasks', $json['listType']);
    }

    public function testGetUriSuccess(): void
    {
        $this->assertEquals('tasks', (new MyTasks())->getUri());
    }

    public function testGetMethodSuccess(): void
    {
        $this->assertEquals('POST', (new MyTasks())->getMethod());
    }
}
