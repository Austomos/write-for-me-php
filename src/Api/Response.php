<?php

namespace Austomos\WriteForMePhp\Api;

use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\Interfaces\ResponseInterface;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use JsonException;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;

class Response extends GuzzleResponse implements ResponseInterface
{
    private array|object $json;

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public function __construct(GuzzleResponseInterface $response)
    {
        parent::__construct(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
        try {
            $this->json = json_decode(
                $this->getBody()->getContents(),
                false,
                512,
                JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY
            );
        } catch (JsonException $e) {
            throw new WriteForMeException('Json decode failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getResponseBody(): array
    {
        return $this->json;
    }
}
