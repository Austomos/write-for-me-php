<?php

namespace Austomos\WriteForMePhp\Api;

use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\Interfaces\ClientInterface;
use Austomos\WriteForMePhp\Interfaces\ResponseInterface;
use Austomos\WriteForMePhp\WriteForMe;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\UriInterface;

abstract class Client extends GuzzleClient implements ClientInterface
{
    abstract protected function getOptions(): array;
    abstract protected function getMethod(): string;
    abstract protected function getUri(): string|UriInterface;

    public function __construct()
    {
        parent::__construct([
            'base_uri' => WriteForMe::BASE_URI,
            'headers' => [
                'Authorization' => 'Bearer ' . WriteForMe::login()->getToken(),
            ]
        ]);
    }

    /**
     * @throws WriteForMeException
     */
    public function requestResponse(): ResponseInterface
    {
        try {
            return new Response($this->request($this->getMethod(), $this->getUri(), $this->getOptions()));
        } catch (GuzzleException $e) {
            throw new WriteForMeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}