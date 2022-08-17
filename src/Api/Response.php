<?php

namespace Austomos\WriteForMePhp\Api;

use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\Interfaces\ResponseInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;

class Response implements ResponseInterface
{
    private array|object $json;
    private GuzzleResponseInterface $response;

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public function __construct(GuzzleResponseInterface $response)
    {
        $this->response = $response;
        try {
            $this->json = json_decode(
                $this->response->getBody()->getContents(),
                false,
                512,
                JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY
            );
        } catch (JsonException $e) {
            throw new WriteForMeException('Json decode failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function response(): GuzzleResponseInterface
    {
        return $this->response;
    }

    public function getResponseBody(): array|object
    {
        return $this->json;
    }

    public function getResponseBodyObject(): object
    {
        return (object) $this->getResponseBody();
    }
    public function getResponseBodyArray(): array
    {
        return (array) $this->getResponseBody();
    }
}
