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
    abstract protected function isValidOptions(): bool;
    abstract protected function getMethod(): string;
    abstract protected function getUri(): string|UriInterface;

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public function __construct()
    {
        try {
            parent::__construct([
                'base_uri' => WriteForMe::BASE_URI,
                'headers' => [
                    'Authorization' => 'Bearer ' . WriteForMe::login()->getToken(),
                ]
            ]);
        } catch (\RuntimeException $e) {
            throw new WriteForMeException('You must login first', 400, $e);
        }
    }

    /**
     * @throws WriteForMeException
     */
    public function requestResponse(): ResponseInterface
    {
        if (!$this->isValidOptions()) {
            throw new WriteForMeException('Invalid arguments provided', 400);
        }
        try {
            return new Response($this->request($this->getMethod(), $this->getUri(), $this->getOptions()));
        } catch (GuzzleException $e) {
            throw new WriteForMeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
