<?php

namespace Austomos\WriteForMePhp\Tests\Api;

use Austomos\WriteForMePhp\Api\Response;
use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ResponseTest extends TestCase
{

    public function testGetResponseBodyObjectSuccess(): void
    {
        $mockResponse = new \GuzzleHttp\Psr7\Response(
            200,
            [],
            '{"mock_key": "mock_value"}'
        );
        try {
            $response = new Response($mockResponse);
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals((object) ['mock_key' => 'mock_value'], $response->getResponseBodyObject());
    }

    public function testConstructException(): void
    {
        $mockResponse = new \GuzzleHttp\Psr7\Response(
            200,
            [],
            'mock_failed_json'
        );

        $this->expectException(WriteForMeException::class);
        new Response($mockResponse);
    }

    public function testGetResponseBodyArraySuccess(): void
    {
        $mockResponse = new \GuzzleHttp\Psr7\Response(
            200,
            [],
            '{"mock_key": "mock_value"}'
        );
        try {
            $response = new Response($mockResponse);
        } catch (WriteForMeException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals(['mock_key' => 'mock_value'], $response->getResponseBodyArray());
    }
}
